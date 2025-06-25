<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Invoice, shipmentContainers, Transaction, TShipments};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use carbon\Carbon;

class PurchaseInvoice extends Component
{
    public $invoice_number, $shipmentId, $vendor_id, $date, $due_date, $total_amount = 0;
    public $status = 'Unpaid', $notes, $currency, $pdfData = '';
    public $shipments, $vendors, $transactions, $containers;
    public $totalPcs = 0;
    public $totalgw = 0;
    public string $finalCurrency = 'IDR'; // default

    protected $listeners = ['setShipmentId'];

    public function mount($shipmentId = null)
    {
        $this->shipmentId = $shipmentId;
        $this->shipments = TShipments::where('id', $shipmentId)->get();

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        // Ambil daftar vendor dari transaksi yang sudah ada
        $this->vendors = Customer::whereIn('id', Transaction::whereNotNull('cvendor')->pluck('cvendor'))->get();
        $this->containers = shipmentContainers::all();

        if ($shipmentId) {
            $this->loadTransactions();
        }
    }

    public function generatePDF()
    {
        if (!$this->shipmentId || !$this->vendor_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'Pilih shipment dan customer yang valid terlebih dahulu.');
            return;
        }

        $shipment = TShipments::with('container', 'shipmentTransaction', 'job')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->vendor_id);

        $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
        $totalgw  = $shipment->container->sum('shipmentGrossWeight');

        $summary = [
            'subtotal' => 0,
            'vat'      => 0,
            'wht'      => 0,
            'total'    => 0,
        ];

        foreach ($this->transactions as $trx) {
            $currency = strtoupper(trim($trx->ccurrency));
            $qty      = (int) ($trx->quantity ?? 0);
            $rate     = (float) ($trx->crate ?? 1);

            // Hitung amount asli
            $amount = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->camountidr)
                : (float) $trx->cfcyamount;

            $vat = $currency === 'IDR'
                ? (float) ($trx->cvatgstamount ?? 0)
                : (float) ($trx->cvatgstusd ?? 0);

            // Hitung WHT, konversi jika bukan IDR
            $wht = $currency === 'IDR'
                ? (float) ($trx->cwhtaxamount ?? 0)
                : ((float) ($trx->cwhtaxamount ?? 0)) / $rate;

            $subtotal = $qty * $amount;
            $total    = $subtotal + $vat + $wht;

            $trx->subtotal = $subtotal;
            $trx->total    = $total;

            // Konversi ke finalCurrency jika berbeda
            if ($currency !== $this->finalCurrency) {
                if ($currency === 'IDR' && $this->finalCurrency === 'USD') {
                    $cSub = $subtotal / $rate;
                    $cVat = $vat / $rate;
                    $cWht = $wht / $rate;
                } elseif ($currency === 'USD' && $this->finalCurrency === 'IDR') {
                    $cSub = $subtotal * $rate;
                    $cVat = $vat * $rate;
                    $cWht = $wht * $rate;
                } else {
                    // fallback jika mata uang lain
                    $cSub = $subtotal;
                    $cVat = $vat;
                    $cWht = $wht;
                }
            } else {
                $cSub = $subtotal;
                $cVat = $vat;
                $cWht = $wht;
            }

            // Akumulasi summary
            $summary['subtotal'] += $cSub;
            $summary['vat']      += $cVat;
            $summary['wht']      += $cWht;
            $summary['total']    += $cSub + $cVat + $cWht;
        }

        // Format summary untuk blade
        $formattedSummary = [];
        foreach (['subtotal', 'vat', 'wht', 'total'] as $key) {
            $formattedSummary[$key] = number_format(
                $summary[$key],
                2,
                $this->finalCurrency === 'IDR' ? ',' : '.',
                $this->finalCurrency === 'IDR' ? '.' : ','
            );
        }

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'       => $totalPcs,
            'totalgw'        => $totalgw,
            'currency'       => $customer->currency,
            'formattedSummary' => $formattedSummary,
            'finalCurrency'  => $this->finalCurrency,
        ];

        $now = Carbon::now();
        $html = view('livewire.accounting.purchasing-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        return response()->streamDownload(
            fn() => print($pdfContent),
            "Invoice-{$this->invoice_number}-{$now->format('YmdHis')}.pdf"
        );
    }

    public function previewPDF()
    {
        if (!$this->shipmentId || !$this->vendor_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = TShipments::with('container', 'shipmentTransaction', 'job')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->vendor_id);
        $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
        $totalgw  = $shipment->container->sum('shipmentGrossWeight');

        // inisialisasi summary
        $summary = [
            'subtotal' => 0,
            'vat'      => 0,
            'wht'      => 0,
            'total'    => 0,
        ];

        foreach ($this->transactions as $trx) {
            $currency = strtoupper(trim($trx->ccurrency));
            $qty      = (int) $trx->quantity;
            $rate     = (float) ($trx->crate ?? 1);

            // === 1) hitung nilai asli per transaksi ===
            $amount = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->camountidr)
                : (float) $trx->cfcyamount;

            $vat = $currency === 'IDR'
                ? (float) ($trx->ctaxableamount ?? 0)
                : (float) ($trx->cvatgstusd    ?? 0);

            $wht = $currency === 'IDR'
                ? (float) ($trx->cwhtaxamount   ?? 0)
                : (float) ($trx->chwtaxrateusd  ?? 0);

            $subtotal = $qty * $amount;
            $total    = $subtotal + $vat + $wht;

            // simpan utk row-table
            $trx->subtotal = $subtotal;
            $trx->vat      = $vat;
            $trx->wht      = $wht;
            $trx->total    = $total;

            // === 2) konversi ke finalCurrency ===
            if ($currency !== $this->finalCurrency) {
                if ($currency === 'IDR' && $this->finalCurrency === 'USD') {
                    $cSub   = $subtotal / $rate;
                    $cVat   = $vat      / $rate;
                    $cWht   = $wht      / $rate;
                } elseif ($currency === 'USD' && $this->finalCurrency === 'IDR') {
                    $cSub   = $subtotal * $rate;
                    $cVat   = $vat      * $rate;
                    $cWht   = $wht      * $rate;
                } else {
                    // fallback—kalau ada mata uang lain, bisa extend di sini
                    $cSub = $subtotal;
                    $cVat = $vat;
                    $cWht = $wht;
                }
            } else {
                // sama dengan finalCurrency, no conversion
                $cSub = $subtotal;
                $cVat = $vat;
                $cWht = $wht;
            }

            // accumulate summary
            $summary['subtotal'] += $cSub;
            $summary['vat']      += $cVat;
            $summary['wht']      += $cWht;
            $summary['total']    += ($cSub + $cVat + $cWht);
        }

        // format summary untuk blade
        $formattedSummary = [
            'subtotal' => number_format(
                $summary['subtotal'],
                2,
                $this->finalCurrency === 'IDR' ? ',' : '.',
                $this->finalCurrency === 'IDR' ? '.' : ','
            ),
            'vat'      => number_format(
                $summary['vat'],
                2,
                $this->finalCurrency === 'IDR' ? ',' : '.',
                $this->finalCurrency === 'IDR' ? '.' : ','
            ),
            'wht'      => number_format(
                $summary['wht'],
                2,
                $this->finalCurrency === 'IDR' ? ',' : '.',
                $this->finalCurrency === 'IDR' ? '.' : ','
            ),
            'total'    => number_format(
                $summary['total'],
                2,
                $this->finalCurrency === 'IDR' ? ',' : '.',
                $this->finalCurrency === 'IDR' ? '.' : ','
            ),
        ];

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'  => $totalPcs,
            'totalgw'  => $totalgw,
            'finalCurrency'        => $this->finalCurrency,
            'formattedSummary'     => $formattedSummary,
            'currency'       => $customer->country,
        ];

        $html = view('livewire.accounting.purchasing-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome') // Make sure this is correct
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();
        $this->pdfData = base64_encode($pdfContent);

        $this->dispatch('open-pdf-preview', pdf: 'data:application/pdf;base64,' . $this->pdfData);
    }

    public function generateInvoiceNumber()
    {
        return "PI-BRN-" . now()->format('ymd') . str_pad(Invoice::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
    }

    public function setShipmentId($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $this->loadTransactions();
    }

    public function updatedVendorId()
    {
        $vendor = Customer::find($this->vendor_id);
        if ($vendor) {
            $this->currency = $vendor->currency;
        }
        $this->loadTransactions();
        $this->pdfData = '';
    }

    public function loadTransactions()
    {
        $this->transactions = ($this->shipmentId && $this->vendor_id)
            ? Transaction::where('id_shipment', $this->shipmentId)->where('cvendor', $this->vendor_id)->get()
            : collect();
    }



    public function render()
    {
        return view('livewire.accounting.purchase-invoice', [
            'invoices'  => Invoice::where('shipment_id', $this->shipmentId)->with(['shipment.containers', 'customer', 'transactions'])->get(),
            'shipments' => $this->shipments,
        ]);
    }
}

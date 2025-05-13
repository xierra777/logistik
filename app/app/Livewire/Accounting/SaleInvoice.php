<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, Transaction, Shipment};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class SaleInvoice extends Component
{
    public string $finalCurrency = 'IDR'; // default
    public $invoice_number, $shipmentId, $customer_id, $date, $due_date, $total_amount = 0;
    public $notes, $currency, $pdfData = '';
    public $shipments, $customers, $transactions, $containers, $clients;
    public $totalPcs = 0;
    public $totalgw = 0;
    public $shipment;

    protected $listeners = ['setShipmentId'];


    public function mount($shipmentId = null)
    {
        $this->shipmentId = $shipmentId;
        $this->shipments = Shipment::where('id', $shipmentId)->get();

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        $shipment = Shipment::with(['shipper', 'consignee', 'notify'])->find($shipmentId);

        $customerNames = collect([
            $shipment->shipper?->name,
            $shipment->consignee?->name,
            $shipment->notify?->name,
        ])->filter();

        $this->clients = Customer::whereIn('name', $customerNames)->get();
        $this->containers = Container::all();

        if ($shipmentId) {
            $this->loadTransactions();
        }
    }

    // Di method generatePDF()
    public function generatePDF()
    {
        // validasi
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers', 'transactions')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw  = $shipment->containers->sum('gross_weight');

        // inisialisasi summary
        $summary = [
            'subtotal' => 0,
            'vat'      => 0,
            'wht'      => 0,
            'total'    => 0,
        ];

        foreach ($this->transactions as $trx) {
            $currency = strtoupper(trim($trx->scurrency));
            $qty      = (int) $trx->quantity;
            $rate     = (float) ($trx->srate ?? 1);

            // === 1) hitung nilai asli per transaksi ===
            $amount = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->samountidr)
                : (float) $trx->sfcyamount;

            $vat = $currency === 'IDR'
                ? (float) ($trx->svatgstamount ?? 0)
                : (float) ($trx->svatgstusd    ?? 0);

            $wht = $currency === 'IDR'
                ? (float) ($trx->swhtaxamount   ?? 0)
                : (float) ($trx->shwtaxrateusd  ?? 0);

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

        // render view
        $data = compact('shipment', 'customer') + [
            'transactions'         => $this->transactions,
            'totalPcs'             => $totalPcs,
            'totalgw'              => $totalgw,
            'formattedSummary'     => $formattedSummary,
            'finalCurrency'        => $this->finalCurrency,
            'invoice_number'       => $this->invoice_number,
        ];

        // Get current date (Optional - can be used for the document or logs)
        $now = Carbon::now();

        // Render the HTML view
        $html = view('livewire.accounting.invoice-pdf', $data)->render();

        // Generate PDF content using Browsershot
        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome') // Make sure this is correct
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        // Return PDF content as a download response
        return response()->streamDownload(
            fn() => print($pdfContent),
            "Invoice-{$this->invoice_number}-{$now}.pdf"
        );
    }
    public function parseIndoNumber($number)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $number)));
    }

    public function previewPDF()
    {
        // validasi
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw  = $shipment->containers->sum('gross_weight');

        // inisialisasi summary
        $summary = [
            'subtotal' => 0,
            'vat'      => 0,
            'wht'      => 0,
            'total'    => 0,
        ];

        foreach ($this->transactions as $trx) {
            $currency = strtoupper(trim($trx->scurrency));
            $qty      = (int) $trx->quantity;
            $rate     = (float) ($trx->srate ?? 1);

            // === 1) hitung nilai asli per transaksi ===
            $amount = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->samountidr)
                : (float) $trx->sfcyamount;

            $vat = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->svatgstamount ?? 0)
                : (float) ($trx->svatgstusd    ?? 0);

            $wht = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($trx->swhtaxamount   ?? 0)
                : (float) ($trx->shwtaxrateusd  ?? 0);

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

        // render view
        $data = compact('shipment', 'customer') + [
            'transactions'         => $this->transactions,
            'totalPcs'             => $totalPcs,
            'totalgw'              => $totalgw,
            'formattedSummary'     => $formattedSummary,
            'finalCurrency'        => $this->finalCurrency,
            'invoice_number'       => $this->invoice_number,
        ];

        $html = view('livewire.accounting.invoice-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        $this->pdfData = base64_encode($pdfContent);
        $this->dispatch('open-pdf-preview', pdf: 'data:application/pdf;base64,' . $this->pdfData);
        // dd($summary);  // Debug summary sebelum format

    }



    public function generateInvoiceNumber()
    {
        return "INV-BRN-" . now()->format('ymd') . str_pad(Invoice::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
    }

    public function setShipmentId($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $this->loadTransactions();
    }

    public function updatedCustomerId()
    {
        $customer = Customer::find($this->customer_id);
        if ($customer) {
            $this->currency = $customer->currency;
        }
        $this->loadTransactions();
        $this->pdfData = '';
    }

    public function loadTransactions()
    { {
            if ($this->shipmentId && $this->customer_id) {
                $this->transactions = Transaction::where('shipment_id', $this->shipmentId)
                    ->where('customer_id', $this->customer_id)
                    ->get();
                // Hitung total pcs dari containers di shipment
                $shipment = Shipment::with('containers')->find($this->shipmentId);
                if ($shipment && $shipment->containers) {
                    $this->totalPcs = $shipment->containers->sum('pcs');
                    $this->totalgw = $shipment->containers->sum('gross_weight');
                }
            } else {
                $this->transactions = collect();
                $this->totalPcs = 0;
                $this->totalgw = 0;
            }
        }
    }
    public function render()
    {
        return view('livewire.accounting.sale-invoice', [
            'invoices'  => Invoice::where('shipment_id', $this->shipmentId)
                ->with(['shipment.containers', 'customer', 'transactions'])
                ->get(),
            'shipments' => $this->shipments,
        ]);
    }
}

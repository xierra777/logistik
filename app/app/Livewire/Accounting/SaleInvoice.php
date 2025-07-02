<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, shipmentContainers, Transaction, TShipments};
use Spatie\Browsershot\Browsershot;

use Illuminate\Database\Eloquent\Collection;

use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class SaleInvoice extends Component
{
    public string $finalCurrency = 'IDR'; // default
    public $invoice_number, $shipmentId, $customer_id, $date, $due_date, $total_amount = 0;
    public $notes, $currency, $pdfData = '';
    public $customers, $transactions, $containers, $clients;
    public $totalPcs = 0;
    public $totalgw = 0;
    public $shipment;
    public $dataShip;
    public $issuedInvoices;
    public $showExchangeRate = false;
    public array $selectedTransactionIds = [];

    protected $listeners = ['setShipmentId'];


    public function mount($shipmentId)
    {

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }
        $shipment = TShipments::with(['shipper', 'consignee', 'notify'])->findOrFail($shipmentId);
        // $shipmentId = TShipments::get();
        // dd($shipmentId);

        // dd($dataShip);
        $customerNames = collect([
            $shipment->client?->name,
            $shipment->shipper?->name,
            $shipment->consignee?->name,
            $shipment->notify?->name,
        ])->filter();

        $this->clients = Customer::whereIn('name', $customerNames)->get();
        // $this->containers = shipmentContainers::all();
        if ($shipmentId) {
            $this->loadTransactions();
        }
    }
    public function selectAllTransactions()
    {
        $allIds = $this->transactions->pluck('id')->toArray();

        if (count($this->selectedTransactionIds) === count($allIds)) {
            // Jika semua sudah dipilih → batalkan semua
            $this->selectedTransactionIds = [];
        } else {
            // Jika belum semua → pilih semua
            $this->selectedTransactionIds = $allIds;
        }
    }
    public function generatePDF()
    {
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = TShipments::with('container', 'shipmentTransaction', 'job')->findOrFail($this->shipmentId);
        $customer = customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
        $totalgw  = $shipment->container->sum('shipmentGrossWeight');

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

            $amount = $currency === 'IDR'
                ? (int) $trx->samountidr
                : (int) $trx->sfcyamount;

            $vat = $currency === 'IDR'
                ? (float) ($trx->svatgstamount ?? 0)
                : (float) ($trx->svatgstusd    ?? 0);

            $wht = $currency === 'IDR'
                ? (float) ($trx->swhtaxamount   ?? 0)
                : (float) ($trx->shwtaxrateusd  ?? 0);

            $subtotal = $qty * $amount;
            $total    = $subtotal + $vat + $wht;

            $trx->subtotal = $subtotal;
            $trx->vat      = $vat;
            $trx->wht      = $wht;
            $trx->total    = $total;
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
                    $cSub = $subtotal;
                    $cVat = $vat;
                    $cWht = $wht;
                }
            } else {
                $cSub = $subtotal;
                $cVat = $vat;
                $cWht = $wht;
            }
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
        $now = Carbon::now();
        $html = view('livewire.accounting.invoice-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();
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
        $selectedTransactions = $this->transactions->filter(function ($trx) {
            return in_array($trx->id, $this->selectedTransactionIds);
        });
        if ($selectedTransactions->isEmpty()) {
            session()->flash('error', 'No transactions selected for preview.');
            return;
        }
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = TShipments::with('container.jobContainer', 'job')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
        $totalgw  = $shipment->container->sum('shipmentGrossWeight');
        // dd($shipment->container->first()->jobContainer->containers);
        $summary = [
            'subtotal' => 0,
            'vat'      => 0,
            'wht'      => 0,
            'total'    => 0,
        ];

        foreach ($selectedTransactions as $trx) {
            $currency = strtoupper(trim($this->finalCurrency));
            $qty      = (int) $trx->quantity;
            $rate     = (float) ($trx->srate ?? 1);

            $amount = $currency === 'IDR'
                ? (int) $trx->samountidr
                : (float) $trx->sfcyamount;

            $vat = $currency === 'IDR'
                ? (float) $trx->svatgstamount
                : (float) ($trx->svatgstusd    ?? 0);

            $wht = $currency === 'IDR'
                ? (float) $trx->swhtaxamount
                : (float) ($trx->shwtaxrateusd  ?? 0);

            $subtotal = $qty * $amount;
            $total    = $subtotal + $vat + $wht;

            $trx->subtotal = $subtotal;
            $trx->vat      = $vat;
            $trx->wht      = $wht;
            $trx->total    = $total;

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
                    $cSub = $subtotal;
                    $cVat = $vat;
                    $cWht = $wht;
                }
            } else {
                $cSub = $subtotal;
                $cVat = $vat;
                $cWht = $wht;
            }

            $summary['subtotal'] += $cSub;
            $summary['vat']      += $cVat;
            $summary['wht']      += $cWht;
            $summary['total']    += ($cSub + $cVat + $cWht);
        }

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
            'transactions'         => $selectedTransactions,
            'totalPcs'             => $totalPcs,
            'totalgw'              => $totalgw,
            'formattedSummary'     => $formattedSummary,
            'finalCurrency'        => $this->finalCurrency,
            'invoice_number'       => $this->invoice_number,
            'showExchangeRate'     => $this->showExchangeRate,
        ];

        $html = view('livewire.accounting.invoice-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome')
            ->format('A4')
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        $this->pdfData = base64_encode($pdfContent);
        $this->dispatch('open-pdf-preview', pdf: 'data:application/pdf;base64,' . $this->pdfData);
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
                $this->transactions = Transaction::where('id_shipment', $this->shipmentId)
                    ->where('sclient', $this->customer_id)
                    ->get();
                // Hitung total pcs dari containers di shipment
                $shipment = TShipments::with('container')->find($this->shipmentId);
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
                ->with(['shipment.container', 'client', 'transactions'])
                ->where('status', '!=', 'draft')
                ->get(),
            'shipment' => TShipments::find($this->shipmentId),
        ]);
    }
}

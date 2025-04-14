<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, Transaction, Shipment};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class SaleInvoice extends Component
{
    public $invoice_number, $shipmentId, $customer_id, $date, $due_date, $total_amount = 0;
    public $notes, $currency, $pdfData = '';
    public $shipments, $customers, $transactions, $containers, $clients;
    public $totalPcs = 0;
    public $totalgw = 0;

    protected $listeners = ['setShipmentId'];

    public function mount($shipmentId = null)
    {
        $this->shipmentId = $shipmentId;
        $this->shipments = Shipment::where('id', $shipmentId)->get();

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        $customerNames = Shipment::where('id', $shipmentId)
            ->selectRaw("shipper AS name")
            ->union(Shipment::selectRaw("consignee AS name")->where('id', $shipmentId))
            ->union(Shipment::selectRaw("notify AS name")->where('id', $shipmentId))
            ->pluck('name');

        $this->clients = Customer::whereIn('name', $customerNames)->get();
        $this->containers = Container::all();

        if ($shipmentId) {
            $this->loadTransactions();
        }
    }

    // Di method generatePDF()
    public function generatePDF()
    {
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'Pilih shipment dan customer yang valid terlebih dahulu.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw = $shipment->containers->sum('gross_weight');

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'       => $this->totalPcs,
            'totalgw'  => $this->totalgw,
            'currency'       => $customer->currency,
        ];
        $now = Carbon::now(); // mendapatkan instance Carbon untuk tanggal dan waktu saat ini
        echo $now->format('d-m-Y'); // misal: "24-03-2025"
        $html = view('livewire.accounting.invoice-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
        ->setChromePath('/usr/bin/google-chrome')
        ->format('A3')
        ->margins(5, 5, 5, 5)
        ->showBackground()
        ->setOption('args', ['--no-sandbox']) 
        ->pdf();

        return response()->streamDownload(fn() => print($pdfContent), "Invoice-{$this->invoice_number}.pdf");
    }
    public function previewPDF()
    {
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw = $shipment->containers->sum('gross_weight');


        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'  => $this->totalPcs,
            'totalgw'  => $this->totalgw,
            'currency'       => $customer->currency,
            'country' => $customer->country,
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

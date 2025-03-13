<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, Transaction, Shipment};
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class SaleInvoice extends Component
{
    public $invoice_number, $shipmentId, $customer_id, $date, $due_date, $total_amount = 0;
    public $status = 'Unpaid', $notes, $currency, $pdfData = '';
    public $shipments, $customers, $transactions, $containers, $clients;

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

    public function generatePDF()
    {
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'Pilih dulu jir customernya 😹');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'currency'       => $customer->currency,
        ];

        $html = view('livewire.accounting.invoice-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)->setOption('no-sandbox', true)->pdf();

        return response()->streamDownload(fn() => print($pdfContent), 'invoice.pdf');
    }

    public function previewPDF()
    {
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'currency'       => $customer->currency,
        ];

        $html = view('livewire.accounting.invoice-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)->setOption('no-sandbox', true)->pdf();
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
    {
        $this->transactions = ($this->shipmentId && $this->customer_id)
            ? Transaction::where('shipment_id', $this->shipmentId)->where('customer_id', $this->customer_id)->get()
            : collect();
    }

    public function save()
    {
        $this->validate([
            'invoice_number' => 'nullable|unique:invoices',
            'shipmentId'     => 'required|exists:shipments,id',
            '   '    => 'required|exists:customers,id',
            'date'           => 'required|date',
            'due_date'       => 'required|date|after_or_equal:date',
            'total_amount'   => 'required|numeric',
            'status'         => 'required|in:Unpaid,Paid,Overdue',
            'notes'          => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $this->invoice_number,
            'shipment_id'    => $this->shipmentId,
            'customer_id'    => $this->customer_id,
            'date'           => $this->date,
            'due_date'       => $this->due_date,
            'total_amount'   => $this->total_amount,
            'status'         => $this->status,
            'notes'          => $this->notes,
            'currency'       => $this->currency,
        ]);

        Transaction::where('shipment_id', $this->shipmentId)
            ->where('customer_id', $this->customer_id)
            ->update(['invoice_id' => $invoice->id]);

        session()->flash('message', 'Sale Invoice created successfully!');
        return redirect()->route('sale-invoice.show', ['id' => $invoice->id]);
    }

    public function render()
    {
        return view('livewire.accounting.sale-invoice', [
            'invoices'  => Invoice::where('shipment_id', $this->shipmentId)->with(['shipment.containers', 'customer', 'transactions'])->get(),
            'shipments' => $this->shipments,
        ]);
    }
}

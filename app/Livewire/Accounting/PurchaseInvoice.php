<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, Transaction, Shipment};
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\View;

class PurchaseInvoice extends Component
{
    public $invoice_number, $shipmentId, $vendor_id, $date, $due_date, $total_amount = 0;
    public $status = 'Unpaid', $notes, $currency, $pdfData = '';
    public $shipments, $vendors, $transactions, $containers;

    protected $listeners = ['setShipmentId'];

    public function mount($shipmentId = null)
    {
        $this->shipmentId = $shipmentId;
        $this->shipments = Shipment::where('id', $shipmentId)->get();

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        // Ambil daftar vendor dari transaksi yang sudah ada
        $this->vendors = Customer::whereIn('id', Transaction::whereNotNull('vendor_id')->pluck('vendor_id'))->get();
        $this->containers = Container::all();

        if ($shipmentId) {
            $this->loadTransactions();
        }
    }

    public function generatePDF()
    {
        if (!$this->shipmentId || !$this->vendor_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'Incomplete data for PDF generation.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $vendor = Customer::findOrFail($this->vendor_id);

        $data = compact('shipment', 'vendor') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'currency'       => $vendor->currency,
        ];

        $html = view('livewire.accounting.purchase-invoice-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)->setOption('no-sandbox', true)->pdf();

        return response()->streamDownload(fn() => print($pdfContent), 'purchase-invoice.pdf');
    }

    public function previewPDF()
    {
        if (!$this->shipmentId || !$this->vendor_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $vendor = Customer::findOrFail($this->vendor_id);

        $data = compact('shipment', 'vendor') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'currency'       => $vendor->currency,
        ];

        $html = view('livewire.accounting.purchase-invoice-pdf', $data)->render();
        $pdfContent = Browsershot::html($html)->setOption('no-sandbox', true)->pdf();
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
            ? Transaction::where('shipment_id', $this->shipmentId)->where('vendor_id', $this->vendor_id)->get()
            : collect();
    }

    public function save()
    {
        $this->validate([
            'invoice_number' => 'nullable|unique:invoices',
            'shipmentId'     => 'required|exists:shipments,id',
            'vendor_id'      => 'required|exists:customers,id',
            'date'           => 'required|date',
            'due_date'       => 'required|date|after_or_equal:date',
            'total_amount'   => 'required|numeric',
            'status'         => 'required|in:Unpaid,Paid,Overdue',
            'notes'          => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => $this->invoice_number,
            'shipment_id'    => $this->shipmentId,
            'customer_id'    => $this->vendor_id,
            'date'           => $this->date,
            'due_date'       => $this->due_date,
            'total_amount'   => $this->total_amount,
            'status'         => $this->status,
            'notes'          => $this->notes,
            'currency'       => $this->currency,
        ]);

        Transaction::where('shipment_id', $this->shipmentId)
            ->where('vendor_id', $this->vendor_id)
            ->update(['invoice_id' => $invoice->id]);

        session()->flash('message', 'Purchase Invoice created successfully!');
        return redirect()->route('purchase-invoice.show', ['id' => $invoice->id]);
    }

    public function render()
    {
        return view('livewire.accounting.purchase-invoice', [
            'invoices'  => Invoice::where('shipment_id', $this->shipmentId)->with(['shipment.containers', 'customer', 'transactions'])->get(),
            'shipments' => $this->shipments,
        ]);
    }
}

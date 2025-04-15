<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\{Customer, Container, Invoice, Transaction, Shipment};
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
            session()->flash('error', 'Pilih shipment dan customer yang valid terlebih dahulu.');
            return;
        }


        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Transaction::findOrFail($this->vendor_id);
        $totalPcs = $shipment->containers->sum('pcs');

        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'       => $this->totalPcs,
            'currency'       => $customer->currency,
        ];
        $now = Carbon::now(); // mendapatkan instance Carbon untuk tanggal dan waktu saat ini
        echo $now->format('d-m-Y'); // misal: "24-03-2025"
        $html = view('livewire.accounting.invoice-pdf', $data)->render();

        $pdfContent = Browsershot::html($html)
            ->setChromePath('/usr/bin/google-chrome') // Make sure this is correct
            ->format('A3')
            ->margins(5, 5, 5, 5)
            ->showBackground()
            ->setOption('args', ['--no-sandbox'])
            ->pdf();

        return response()->streamDownload(fn() => print($pdfContent), "Invoice-{$this->invoice_number}.pdf");
    }
    public function previewPDF()
    {
        if (!$this->shipmentId || !$this->vendor_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'No data available for preview.');
            return;
        }

        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->vendor_id);
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw = $shipment->containers->sum('gross_weight');


        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'  => $this->totalPcs,
            'totalgw'  => $this->totalgw,
            'currency'       => $customer->currency,
        ];

        $html = view('livewire.accounting.invoice-pdf', $data)->render();
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
            ? Transaction::where('shipment_id', $this->shipmentId)->where('vendor_id', $this->vendor_id)->get()
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

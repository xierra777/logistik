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
        if (!$this->shipmentId || !$this->customer_id || $this->transactions->isEmpty()) {
            session()->flash('error', 'Pilih shipment dan customer yang valid terlebih dahulu.');
            return;
        }

        // Get Shipment and Customer
        $shipment = Shipment::with('containers')->findOrFail($this->shipmentId);
        $customer = Customer::findOrFail($this->customer_id);

        $total = 0;

        foreach ($this->transactions as $transaction) {
            $samount = $this->parseIndoNumber($transaction->samountidr);
            $quantity = $this->parseIndoNumber($transaction->quantity);

            $total += $samount * $quantity;
        }

        $formattedTotal = number_format($total, 2, ',', '.');
        // Calculate totals
        $totalPcs = $shipment->containers->sum('pcs');
        $totalgw = $shipment->containers->sum('gross_weight');

        // Prepare data for the view
        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'       => $totalPcs,
            'totalgw'         => $totalgw,
            'currency'        => $customer->currency,
            'formattedTotal' => $formattedTotal,
        ];

        // Get current date (Optional - can be used for the document or logs)
        $now = Carbon::now();
        $formattedDate = $now->format('d-m-Y');  // For example: "24-03-2025"

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
            "Invoice-{$this->invoice_number}.pdf"
        );
    }
    public function parseIndoNumber($number)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $number)));
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

        // Inisialisasi total berdasarkan mata uang
        $totals = [];
        $formattedTotals = [];

        foreach ($this->transactions as $transaction) {
            $currency = strtoupper(trim($transaction->scurrency));
            $qty = (int) $transaction->quantity;

            $amount = $currency === 'IDR'
                ? (float) $this->parseIndoNumber($transaction->samountidr)
                : (float) $transaction->sfcyamount;

            $vat = $currency === 'IDR'
                ? (float) ($transaction->svatgstamount ?? 0)
                : (float) ($transaction->svatgstusd ?? 0);

            $wht = $currency === 'IDR'
                ? (float) ($transaction->swhtaxamount ?? 0)
                : (float) ($transaction->shwtaxrateusd ?? 0);

            $subtotal = $qty * $amount;
            $total = $subtotal + $vat + $wht;

            $transaction->subtotal = $subtotal;
            $transaction->total = $total;

            if (!isset($totals[$currency])) {
                $totals[$currency] = 0;
            }
            $totals[$currency] += $total;
        }

        // Format total akhir
        foreach ($totals as $curr => $total) {
            $formattedTotals[$curr] = $curr === 'IDR'
                ? number_format($total, 2, ',', '.')
                : number_format($total, 2, '.', ',');
        }

        // Simpan subtotal ke dalam transaksi biar bisa dipakai di blade (optional)
        // Data ke PDF
        $data = compact('shipment', 'customer') + [
            'invoice_number' => $this->invoice_number,
            'transactions'   => $this->transactions,
            'totalPcs'       => $totalPcs,
            'totalgw'        => $totalgw,
            'currency'       => $customer->currency,
            'country'        => $customer->country,
            'formattedTotals' => $formattedTotals,
            'vat'            => $vat,
            'wht'            => $wht,
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

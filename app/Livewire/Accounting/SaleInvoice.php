<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Shipment;
use App\Models\Customer;
use App\Models\Container;

class SaleInvoice extends Component
{
    public $invoice_number;
    public $shipmentId;         // Menggunakan camelCase, bukan shipment_id
    public $customer_id;        // Masih menggunakan customer_id untuk konsistensi dengan database
    public $date;
    public $due_date;
    public $total_amount = 0;
    public $status = 'Unpaid';
    public $notes;
    public $currency;

    // Untuk data reference
    public $shipments, $customers, $transactions, $containers, $clients;

    protected $listeners = ['setShipmentId'];

    /**
     * Jika shipmentId dioper dari parent (ViewShipments), simpan nilainya dan load transaksi.
     */

    public function mount($shipmentId = null)
    {
        $this->shipmentId = $shipmentId;

        // Ambil shipment yang sesuai
        $this->shipments = Shipment::where('id', $shipmentId)->get();
        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }
        // Ambil daftar nama pelanggan dari shipment (shipper, consignee, notify)
        $customerNames = Shipment::where('id', $shipmentId)
            ->selectRaw("shipper AS name")
            ->union(Shipment::selectRaw("consignee AS name")->where('id', $shipmentId))
            ->union(Shipment::selectRaw("notify AS name")->where('id', $shipmentId))
            ->pluck('name');  // Hasilnya list nama pelanggan

        // Cari customer berdasarkan nama yang ditemukan di shipment
        $this->clients = Customer::whereIn('name', $customerNames)->get();

        $this->containers = Container::all();

        if ($shipmentId) {
            $this->loadTransactions();
        }
    }
    public function getSamountidrAttribute($value)
    {
        return floatval(str_replace(['.', ','], ['', '.'], $value));
    }

    public function getSvatgstamountAttribute($value)
    {
        return floatval(str_replace(['.', ','], ['', '.'], $value));
    }
    public function generateInvoiceNumber()
    {
        $prefix = "INV-BRN-";
        $datePart = date('ymd'); // Ambil format YYMMDD (Tahun, Bulan, Hari)

        // Hitung jumlah invoice yang dibuat pada hari ini
        $latestInvoice = Invoice::whereDate('created_at', today())->count() + 1;

        $increment = str_pad($latestInvoice, 3, '0', STR_PAD_LEFT); // Format jadi 3 digit (001, 002, ...)

        return $prefix . $datePart . $increment;
    }

    /**
     * Listener untuk menerima shipmentId dari parent.
     */
    public function setShipmentId($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $this->loadTransactions();
    }

    /**
     * Saat client dipilih, update transaksi terkait.
     */
    public function updatedCustomerId()
    {
        $customer = Customer::find($this->customer_id);
        if ($customer) {
            // Set currency dari client (atau ambil dari transaksi nantinya)
            $this->currency = $customer->currency ?? 'IDR';
        }
        $this->loadTransactions();
    }

    /**
     * Muat transaksi berdasarkan shipment dan client yang dipilih.
     * Hitung total_amount dan set currency berdasarkan transaksi pertama.
     */
    public function loadTransactions()
    {
        if ($this->shipmentId && $this->customer_id) {
            $this->transactions = Transaction::where('shipment_id', $this->shipmentId)
                ->where('customer_id', $this->customer_id)
                ->get();

            // Hitung total_amount (gunakan field sgrossprofit atau samountidr sesuai kebutuhan)
            $this->total_amount = $this->transactions->sum(function ($transaction) {
                return floatval($transaction->sgrossprofit ?? 0);
            });

            // Jika ada transaksi, set currency berdasarkan field scurrency transaksi pertama
            if ($this->transactions->isNotEmpty()) {
                $this->currency = $this->transactions->first()->scurrency;
            }
        } else {
            $this->transactions = collect();
            $this->total_amount = 0;
        }
    }

    /**
     * Simpan invoice dan update transaksi terkait untuk mengaitkan invoice_id.
     */
    public function save()
    {
        $this->validate([
            'invoice_number' => 'nullable|unique:invoices',
            'shipmentId'     => 'required|exists:shipments,id',
            'customer_id'    => 'required|exists:customers,id',
            'date'           => 'required|date',
            'due_date'       => 'required|date|after_or_equal:date',
            'total_amount'   => 'required|numeric',
            'status'         => 'required|in:Unpaid,Paid,Overdue',
            'notes'          => 'nullable|string',
        ]);

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

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
        $invoices = Invoice::where('shipment_id', $this->shipmentId)
            ->with(['shipment.containers', 'customer', 'transactions'])
            ->get();

        return view('livewire.accounting.sale-invoice', [
            'invoices' => $invoices,
            'shipments' => $this->shipments,  // pastikan ini dioper ke view
        ]);
    }
}

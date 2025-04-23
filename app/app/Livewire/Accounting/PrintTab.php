<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Invoice;

class PrintTab extends Component
{
    public $shipmentId;
    public $selectedType = 'sale'; // 'sale' atau 'purchase'
    public $invoices = [];
    public $selectedInvoice = null; // Untuk menyimpan data invoice yang dipilih (untuk menampilkan pengeluaran)

    public function mount($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $this->loadInvoices();
    }

    public function updatedSelectedType()
    {
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        // Ambil invoice berdasarkan shipment dan tipe (pastikan field 'type' ada di tabel invoices)
        $this->invoices = Invoice::where('shipment_id', $this->shipmentId)
            ->get();
    }

    // Method yang dipanggil ketika vendor/client di-click, untuk menampilkan rincian pengeluaran
    public function showExpenses($invoiceId)
    {
        $this->selectedInvoice = Invoice::with('items')->find($invoiceId);
        // Misal, kamu bisa memicu event JavaScript untuk membuka modal,
        // namun di sini kita akan langsung menampilkan modal melalui Blade.
    }

    public function closeModal()
    {
        $this->selectedInvoice = null;
    }

    public function render()
    {
        return view('livewire.accounting.print-tab');
    }
}

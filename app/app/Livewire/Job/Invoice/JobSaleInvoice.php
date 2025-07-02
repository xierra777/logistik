<?php

namespace App\Livewire\Job\Invoice;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\TJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class JobSaleInvoice extends Component
{
    public $jobId;
    public $job;
    public $customer;
    public $transactions;
    public $selected_transactions = [];
    public $selectedTransactionIds = []; // Add this property for the checkboxes
    public $invoice_number;
    public $currency;
    public $showExchangeRate = false; // Add this property
    public $finalCurrency = 'IDR'; // Add this property

    public function mount($jobId)
    {
        $this->jobId = $jobId;

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        $this->job = TJob::with('client')->findOrFail($jobId);

        // Load job dan relasi customer
        $this->customer = $this->job->customer;

        // Load transactions - this was missing!
        $this->loadTransactions();
    }

    public function loadTransactions()
    {

        // Load transactions for this job
        // Adjust this query based on your database structure
        $this->transactions = Transaction::where('id_job', $this->jobId)
            ->get(); // This returns a Collection, not an array

        // Alternative if your relationship is different:
        // $this->transactions = $this->job->transactions;

        // Or if you need to get transactions from a different relationship:
        // $this->transactions = collect([]); // Empty collection as fallback
    }

    public function generateInvoiceNumber()
    {
        return "INV-BRN-" . now()->format('ymd') . str_pad(Invoice::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
    }

    public function selectAllTransactions()
    {
        if (count($this->selectedTransactionIds) === $this->transactions->count()) {
            $this->selectedTransactionIds = [];
        } else {
            $this->selectedTransactionIds = $this->transactions->pluck('id')->toArray();
        }
    }

    public function generatePDF()
    {
        // Add your PDF generation logic here
        session()->flash('message', 'PDF generated successfully!');
    }

    public function previewPDF()
    {
        // Add your PDF preview logic here
        session()->flash('message', 'PDF preview ready!');
    }

    public function save()
    {
        // Validasi minimal ada transaksi yang dipilih
        $this->validate([
            'selectedTransactionIds' => 'required|array|min:1',
        ], [
            'selectedTransactionIds.required' => 'Please select at least one transaction.',
            'selectedTransactionIds.min' => 'Please select at least one transaction.',
        ]);

        // Add your save logic here
    }

    public function render()
    {
        return view('livewire.job.invoice.job-sale-invoice', [
            'job' => $this->job,
            'customer' => $this->customer,
            'transactions' => $this->transactions,
        ]);
    }
}

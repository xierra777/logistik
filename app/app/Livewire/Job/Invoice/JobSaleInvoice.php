<?php

namespace App\Livewire\Job\Invoice;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\TJob;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

class JobSaleInvoice extends Component
{
    public $jobId, $invoiceId;
    public $job;
    public $customer;
    public $id_job;
    public $transactions;
    public $selected_transactions = [];
    public $selectedTransactionIds = []; // Add this property for the checkboxes
    public $invoice_number;
    public $currency, $invoicesIssued;
    public $showExchangeRate = false; // Add this property
    public $finalCurrency = 'IDR'; // Add this property
    public $pdfData = '';

    public function mount($jobId)
    {
        $this->jobId = $jobId;

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }

        $this->job = TJob::with('client')->findOrFail($jobId);

        // Load job dan relasi customer
        $this->customer = $this->job->client;

        // Load transactions - this was missing!
        $this->loadTransactions();
    }

    public function loadTransactions()
    {

        // Load transactions for this job
        // Adjust this query based on your database structure
        $this->transactions = Transaction::where('id_job', $this->jobId)
            ->whereNull('invoice_id')
            ->get();
        $this->invoicesIssued = Invoice::where('job_id', $this->jobId)->get();
        // Alternative if your relationship is different:
        // $this->transactions = $this->job->transactions;

        // Or if you need to get transactions from a different relationship:
        // $this->transactions = collect([]); // Empty collection as fallback
    }

    public function generateInvoiceNumber()
    {
        // Get the last invoice_number that matches today's pattern
        $prefix = "INV-BRN-" . now()->format('ymd');
        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->first();

        if ($lastInvoice) {
            // Extract the last 3 digits and increment
            $lastNumber = (int)substr($lastInvoice->invoice_number, -3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
    public function previewPDF($invoiceId)
    {
        if ($invoiceId) {
            // Gunakan parameter $invoiceId yang diterima, bukan $this->invoiceId
            $invoice = Invoice::with('transactions', 'client', 'job')->findOrFail($invoiceId);

            // Pastikan ada relasi yang benar untuk mengakses job dan customer
            // Asumsi invoice memiliki relasi ke job atau shipment memiliki relasi ke job
            $job = TJob::with('TjobContainer')->findOrFail($invoice->job_id);
            $customer = $invoice->client;

            $totalPcs = $job->TjobContainer->sum('noOfPackages');
            $totalgw  = $job->TjobContainer->sum('grossWeight');

            $summary = [
                'subtotal' => 0,
                'vat'      => 0,
                'wht'      => 0,
                'total'    => 0,
            ];

            foreach ($invoice->transactions as $trx) {
                $currency = strtoupper(trim($this->finalCurrency));
                $qty      = (int) $trx->quantity;
                $rate     = (float) ($trx->srate ?? 1);

                $amount = $currency === 'IDR'
                    ? (int) $trx->samountidr
                    : (float) $trx->sfcyamount;

                $vat = $currency === 'IDR'
                    ? (float) $trx->svatgstamount
                    : (float) ($trx->svatgstusd ?? 0);

                $wht = $currency === 'IDR'
                    ? (float) $trx->swhtaxamount
                    : (float) ($trx->shwtaxrateusd ?? 0);

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
            $data = compact('customer') + [
                'invoice'              => $invoice,
                'job'                   => $invoice->job,
                'container'             => $invoice->job->jobContainer,
                'transactions'         => $invoice->transactions, // Perbaikan: gunakan semua transactions
                'totalPcs'             => $totalPcs,
                'totalgw'              => $totalgw,
                'formattedSummary'     => $formattedSummary,
                'finalCurrency'        => $this->finalCurrency,
                'invoice_number'       => $invoice->invoice_number, // Ambil dari invoice
                'showExchangeRate'     => $this->showExchangeRate,
            ];

            $html = view('livewire.job.invoice.sale-invoice-pdf', $data)->render();

            $pdfContent = Browsershot::html($html)
                ->setChromePath('/usr/bin/google-chrome')
                ->format('A4')
                ->showBackground()
                ->margins(1, 1, 1, 1)
                ->setOption('args', ['--no-sandbox'])
                ->pdf();

            $this->pdfData = base64_encode($pdfContent);
            $this->dispatch('open-pdf-preview', pdf: 'data:application/pdf;base64,' . $this->pdfData);
        }
    }
    public function save()
    {
        // Validasi minimal ada transaksi yang dipilih
        $this->validate([
            'selectedTransactionIds' => 'required|array|min:1',
            'invoice_number' => 'required|string|max:255',
        ], [
            'selectedTransactionIds.required' => 'Please select at least one transaction.',
            'selectedTransactionIds.min' => 'Please select at least one transaction.',
            'invoice_number.required' => 'Invoice number is required.',
        ]);

        DB::beginTransaction();

        try {
            // Ambil transaksi yang dipilih dan belum di-invoice
            $selectedTransactions = Transaction::whereIn('id', $this->selectedTransactionIds)
                ->whereNull('invoice_id')
                ->get();

            if ($selectedTransactions->isEmpty()) {
                DB::rollBack();
                session()->flash('error', 'All selected transactions are already attached to an invoice.');
                return;
            }

            // Hitung subtotal, VAT, WHT, dan grand total
            $subtotal   = $selectedTransactions->sum('samountidr');
            $totalVat   = $selectedTransactions->sum('svatgstamount');
            $totalWht   = $selectedTransactions->sum('swhtaxamount');
            $grandTotal = $subtotal + $totalVat + $totalWht;
            // dd($this->jobId);
            // Buat invoice baru
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'job_id'         => $this->jobId,
                'customer_id'    => $this->customer->id,
                'invoice_date'   => $this->invoice_date ?? now(),
                'due_date'       => now()->addDays(30),
                'status'         => 'issued',
                'currency'       => $this->currency ?? 'IDR',
                'total_amount'   => $grandTotal,
                'created_by'     => Auth::id(),
                'updated_by'     => Auth::id(),
            ]);

            // Update transaksi dengan invoice_id
            Transaction::whereIn('id', $this->selectedTransactionIds)->update([
                'invoice_id' => $invoice->id
            ]);

            // Buat jurnal untuk setiap transaksi
            foreach ($selectedTransactions as $transaction) {
                $saleCoa = ChartOfAccount::find($transaction->coa_sale_id);
                $costCoa = ChartOfAccount::find($transaction->coa_cost_id);

                // Jurnal Penjualan (Revenue)
                if ($transaction->samountidr && $saleCoa && $transaction->transactionClient) {
                    $saleAmount = $transaction->samountidr;
                    $vatAmount  = $transaction->svatgstamount;
                    $whtAmount  = $transaction->swhtaxamount;
                    $totalSale  = $saleAmount + $vatAmount - $whtAmount;

                    // Piutang (A/R) - Debit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $transaction->transactionClient->coa_id,
                        'debit'               => $totalSale,
                        'credit'              => 0,
                        'description'         => "Piutang dari transaksi #{$transaction->transactionClient->name} ({$transaction->job->job_id}) - {$transaction->description}",
                        'transactionable_type' => get_class($transaction),
                        'transactionable_id'  => $transaction->id,
                        'date'                => now(),
                        'created_by'          => Auth::id(),
                    ]);

                    // VAT Output - Kredit
                    if ($vatAmount > 0 && $transaction->saleVat && $transaction->saleVat->coa_id) {
                        JournalEntry::create([
                            'transaction_id'      => $transaction->id,
                            'coa_id'              => $transaction->saleVat->coa_id,
                            'debit'               => 0,
                            'credit'              => $vatAmount,
                            'description'         => "PPN dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                            'transactionable_type' => get_class($transaction),
                            'transactionable_id'  => $transaction->id,
                            'date'                => now(),
                            'created_by'          => Auth::id(),
                        ]);
                    }

                    // WHT Receivable - Debit
                    if ($whtAmount > 0 && $transaction->saleWht && $transaction->saleWht->coa_id) {
                        JournalEntry::create([
                            'transaction_id'      => $transaction->id,
                            'coa_id'              => $transaction->saleWht->coa_id,
                            'debit'               => $whtAmount,
                            'credit'              => 0,
                            'description'         => "PPh 23 dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                            'transactionable_type' => get_class($transaction),
                            'transactionable_id'  => $transaction->id,
                            'date'                => now(),
                            'created_by'          => Auth::id(),
                        ]);
                    }

                    // Pendapatan (Revenue) - Kredit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $saleCoa->id,
                        'debit'               => 0,
                        'credit'              => $saleAmount,
                        'description'         => "Sale transaction #{$transaction->reference_type} ({$transaction->job->job_id}) - {$transaction->description}",
                        'transactionable_type' => $transaction->reference_type,
                        'transactionable_id'  => $transaction->id,
                        'date'                => now(),
                        'created_by'          => Auth::id(),
                    ]);
                }
            }

            DB::commit();
            session()->flash('message', 'Invoice created successfully!');
            // Optionally redirect or reset form here

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create invoice: ' . $e->getMessage());
        }
        $this->loadTransactions();
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

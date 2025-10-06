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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;
use Livewire\Attributes\On;

class JobSaleInvoice extends Component
{
    public $jobId, $invoiceId, $isVoiding;
    public $job;
    public $customer, $void_reason;
    public $id_job;
    public $transactions;
    public $selected_transactions = [];
    public array $selectedTransactionIds = [];
    public $invoice_number;
    public $currency, $invoicesIssued;
    public $showExchangeRate = false; // Add this property
    public $finalCurrency = 'IDR'; // Add this property
    public $pdfData = '';
    public $selectAll = false;
    public $showModal = false;
    public $isIndeterminate = false, $voidReason_job_sale_invoice = false; // Tambahkan ini


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
        $this->transactions = Transaction::where('id_job', $this->jobId)
            ->whereNull('invoice_id')
            ->where('samountidr', '!=', 0)  // Exclude nilai 0
            ->get();
        $this->invoicesIssued = Invoice::where('job_id', $this->jobId)->where('type_invoice', 'SALES')->get();
    }
    #[On('issueInvoice')]
    public function issueInvoice($id)
    {
        // Ambil invoice berdasarkan $id
        $invoice = Invoice::findOrFail($id);

        // Ambil semua transaksi yang berelasi dengan invoice tersebut
        $transactions = Transaction::where('invoice_id', $invoice->id)->get();

        // Update semua transaksi, bisa pakai query langsung:

        foreach ($transactions as $transaction) {
            $transaction->update(['is_invoiced' => true]);
        }
        $invoice->update([
            'status' => 'issued',
            'due_date' => now()->addDays(30),
            'updated_by' => Auth::user()->id,
        ]);


        $this->dispatch('showSuccessAlert', [
            'title' => 'Invoice Issued!',
            'text'  => "Invoice #{$invoice->invoice_number} has been marked as issued.",
            'icon'  => 'success'
        ]);
        $selectedTransactions = $invoice->transactions;
        foreach ($selectedTransactions as $transaction) {
            $saleCoa = ChartOfAccount::find($transaction->coa_sale_id);

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
                    'invoice_id'             => $invoice->id,
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
                        'invoice_id'             => $invoice->id,
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
                        'invoice_id'             => $invoice->id,
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
                    'invoice_id'             => $invoice->id,
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
        $this->loadTransactions();
    }
    public function confirmVoid($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->showModal = true;
        $invoice = Invoice::findOrFail($invoiceId);
        $this->invoice_number = $invoice->invoice_number;
        $this->void_reason = ''; // Reset reason
    }

    public function reasonVoidingJobSaleInvoice($id)
    {
        $this->voidReason_job_sale_invoice = true;
        $invoice = Invoice::findOrFail($id);
        $this->invoice_number = $invoice->invoice_number;
        $this->void_reason = $invoice->void_reason;
    }
    public function cancelReasonVoidingJobSaleInvoice()
    {
        $this->voidReason_job_sale_invoice = false;
    }
    public function voidInvoice()
    {
        // Validate the void reason
        $this->validate([
            'void_reason' => 'required|string|max:255',
        ]);

        $invoice = Invoice::findOrFail($this->invoiceId);

        // Update invoice status
        $invoice->update([
            'status' => 'void',
            'due_date' => null,
            'void_reason' => $this->void_reason,
            'updated_by' => Auth::user()->id
        ]);

        // Create reversal journal entries
        $journalEntries = JournalEntry::where('invoice_id', $invoice->id)->get();

        foreach ($journalEntries as $entry) {
            JournalEntry::create([
                'invoice_id'           => $entry->invoice_id,
                'transaction_id'       => $entry->transaction_id,
                'coa_id'               => $entry->coa_id,
                'debit'                => $entry->credit, // dibalik
                'credit'               => $entry->debit,  // dibalik
                'description'          => "REVERSE: " . $entry->description,
                'transactionable_type' => $entry->transactionable_type,
                'transactionable_id'   => $entry->transactionable_id,
                'is_reversal'          => true,
                'reversal_of'          => $entry->id,
                'date'                 => now(),
                'created_by'           => Auth::id(),
                'updated_by'           => Auth::id(),
            ]);
        }

        // Update related transactions
        if (Schema::hasColumn('transactions', 'invoice_id')) {
            Transaction::where('invoice_id', $invoice->id)->update([
                'invoice_id' => null,
            ]);
        }

        // Close modal and reset form
        $this->showModal = false;
        $this->void_reason = '';
        $this->invoiceId = null;

        // Show correct success message
        $this->dispatch('showSuccessAlert', [
            'title' => 'Invoice Voided!',
            'icon'  => 'success',
            'text'  => "Invoice #{$invoice->invoice_number} has been successfully voided.",
        ]);

        $this->loadTransactions();
    }

    public function cancelVoid()
    {
        $this->showModal = false;
        $this->void_reason = '';
        $this->invoiceId = null;
    }
    public function generateInvoiceNumber()
    {
        try {
            $prefix = "INV-BRN-" . now()->format('ym');

            // Get the highest number for today using raw SQL for better performance
            $result = DB::select("
                SELECT invoice_number 
                FROM invoices 
                WHERE invoice_number LIKE ? 
                ORDER BY CAST(SUBSTRING(invoice_number, -3) AS UNSIGNED) DESC 
                LIMIT 1
            ", [$prefix . '%']);

            if (!empty($result)) {
                $lastNumber = (int)substr($result[0]->invoice_number, -3);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } catch (\Exception $e) {
            // Fallback jika ada error
            Log::error('Error generating invoice number: ' . $e->getMessage());
            return $prefix . str_pad(1, 3, '0', STR_PAD_LEFT);
        }
    }


    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedTransactionIds = $this->transactions->pluck('id')->toArray();
        } else {
            $this->selectedTransactionIds = [];
        }

        $this->updateIndeterminateState();
    }

    public function updatedSelectedTransactionIds()
    {
        $totalTransactions = $this->transactions->count();
        $selectedCount = count($this->selectedTransactionIds);

        if ($selectedCount === $totalTransactions && $totalTransactions > 0) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }

        $this->updateIndeterminateState();
    }

    public function updateIndeterminateState()
    {
        $selectedCount = count($this->selectedTransactionIds);
        $totalCount = $this->transactions->count();

        $this->isIndeterminate = $selectedCount > 0 && $selectedCount < $totalCount;
    }
    public function generatePDF($invoiceId)
    {
        if ($invoiceId) {
            // Gunakan eager loading yang konsisten
            $invoice = Invoice::with([
                'transactions',
                'client',
                'job.TjobContainer' // atau 'job.jobContainer' sesuai nama relasi
            ])->findOrFail($invoiceId);

            // Akses langsung dari relasi yang sudah di-load
            $job = $invoice->job;
            $customer = $invoice->client;

            // Pastikan relasi ada sebelum mengakses
            if ($job && $job->TjobContainer) {
                $totalPcs = $job->TjobContainer->sum('noOfPackages');
                $totalgw = $job->TjobContainer->sum('grossWeight');
            } else {
                $totalPcs = 0;
                $totalgw = 0;
            }

            $summary = [
                'subtotal' => 0,
                'vat'      => 0,
                'wht'      => 0,
                'total'    => 0,
            ];

            if ($invoice->status === 'void') {
                foreach ($invoice->invtrx as $pivot) {
                    $currency = strtoupper(trim($this->finalCurrency));
                    $qty =  $pivot->quantityInvoice;


                    // Ambil amount dari pivot (snapshot)
                    $amount = $currency === 'IDR'
                        ?  $pivot->amountInvoice
                        :  $pivot->amountInvoiceUsd;

                    // VAT dan WHT seharusnya dari field yang sesuai di pivot
                    $vat = $currency === 'IDR'
                        ?  $pivot->vatInvoice
                        :  $pivot->vatInvoiceUsd;
                    $wht = $currency === 'IDR'
                        ?  $pivot->whtInvoice
                        :  $pivot->whtInvoiceUsd;

                    $subtotal = $qty * $amount;
                    $total = $subtotal + $vat + $wht;

                    $pivot->subtotal = $subtotal;
                    $pivot->vat = $vat;
                    $pivot->wht = $wht;
                    $pivot->total = $total;

                    // Akumulasi untuk summary
                    $summary['subtotal'] += $subtotal;
                    $summary['vat'] += $vat;
                    $summary['wht'] += $wht;
                    $summary['total'] += $total;
                }
            } else {
                foreach ($invoice->transactions as $trx) {
                    $currency = strtoupper(trim($this->finalCurrency));

                    $qty = (int) $trx->quantity;
                    $rate = (float) ($trx->srate ?? 1);
                    // dd('Masuk ke bagian else - invoice normal', $invoice->status, $invoice->transactions->count());

                    // PERBAIKAN: Pastikan menggunakan field yang benar dari transaction
                    $amount = $currency === 'IDR'
                        ? (float) $trx->samountidr
                        : (float) $trx->sfcyamount;

                    $vat = $currency === 'IDR'
                        ? (float) ($trx->svatgstamount ?? 0)
                        : (float) ($trx->svatgstusd ?? 0);

                    $wht = $currency === 'IDR'
                        ? (float) ($trx->swhtaxamount ?? 0)
                        : (float) ($trx->shwtaxrateusd ?? 0);

                    // PERBAIKAN: Kalkulasi subtotal berdasarkan qty * rate per unit
                    // Bukan qty * amount (karena amount sudah total)
                    $unitPrice = $amount / $qty; // Hitung unit price
                    $subtotal = $qty * $unitPrice;

                    // Atau jika amount sudah merupakan subtotal:
                    // $subtotal = $amount;

                    $total = $subtotal + $vat + $wht;

                    // PERBAIKAN: Assign nilai ke transaction object agar bisa diakses di view
                    $trx->subtotal = $subtotal;
                    $trx->vat = $vat;
                    $trx->wht = $wht;
                    $trx->total = $total;

                    // Currency conversion jika diperlukan
                    if ($currency !== $this->finalCurrency) {
                        if ($currency === 'IDR' && $this->finalCurrency === 'USD') {
                            $cSub = $subtotal / $rate;
                            $cVat = $vat / $rate;
                            $cWht = $wht / $rate;
                        } elseif ($currency === 'USD' && $this->finalCurrency === 'IDR') {
                            $cSub = $subtotal * $rate;
                            $cVat = $vat * $rate;
                            $cWht = $wht * $rate;
                        } else {
                            $cSub = $subtotal;
                            $cVat = $vat;
                            $cWht = $wht;
                        }

                        // Update transaction object dengan nilai yang sudah dikonversi
                        $trx->subtotal = $cSub;
                        $trx->vat = $cVat;
                        $trx->wht = $cWht;
                        $trx->total = $cSub + $cVat + $cWht;
                    } else {
                        $cSub = $subtotal;
                        $cVat = $vat;
                        $cWht = $wht;
                    }

                    $summary['subtotal'] += $cSub;
                    $summary['vat'] += $cVat;
                    $summary['wht'] += $cWht;
                    $summary['total'] += ($cSub + $cVat + $cWht);
                }
            }

            // Format summary
            $formattedSummary = [
                'subtotal' => number_format(
                    $summary['subtotal'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'vat' => number_format(
                    $summary['vat'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'wht' => number_format(
                    $summary['wht'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'total' => number_format(
                    $summary['total'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
            ];

            // Render view - gunakan data yang konsisten
            $data = [
                'customer'             => $customer,
                'invoice'              => $invoice,
                'job'                  => $invoice->job,
                'container'            => $invoice->job->TjobContainer ?? collect(), // fallback jika null
                'transactions'         => $invoice->transactions,
                'totalPcs'             => $totalPcs,
                'totalgw'              => $totalgw,
                'formattedSummary'     => $formattedSummary,
                'finalCurrency'        => $this->finalCurrency,
                'invoice_number'       => $invoice->invoice_number,
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

            $filename = '' . $data['invoice']->invoice_number . '_' . date('YmdHis') . '.pdf';

            return response()->streamDownload(function () use ($pdfContent) {
                echo $pdfContent;
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }
    }
    public function previewPDF($invoiceId)
    {
        if ($invoiceId) {
            // Gunakan eager loading yang konsisten
            $invoice = Invoice::with([
                'transactions',
                'client',
                'job.TjobContainer' // atau 'job.jobContainer' sesuai nama relasi
            ])->findOrFail($invoiceId);

            // Akses langsung dari relasi yang sudah di-load
            $job = $invoice->job;
            $customer = $invoice->client;

            // Pastikan relasi ada sebelum mengakses
            if ($job && $job->TjobContainer) {
                $totalPcs = $job->TjobContainer->sum('noOfPackages');
                $totalgw = $job->TjobContainer->sum('grossWeight');
            } else {
                $totalPcs = 0;
                $totalgw = 0;
            }

            $summary = [
                'subtotal' => 0,
                'vat'      => 0,
                'wht'      => 0,
                'total'    => 0,
            ];

            if ($invoice->status === 'void') {
                foreach ($invoice->invtrx as $pivot) {
                    $currency = strtoupper(trim($this->finalCurrency));
                    $qty =  $pivot->quantityInvoice;


                    // Ambil amount dari pivot (snapshot)
                    $amount = $currency === 'IDR'
                        ?  $pivot->amountInvoice
                        :  $pivot->amountInvoiceUsd;

                    // VAT dan WHT seharusnya dari field yang sesuai di pivot
                    $vat = $currency === 'IDR'
                        ?  $pivot->vatInvoice
                        :  $pivot->vatInvoiceUsd;
                    $wht = $currency === 'IDR'
                        ?  $pivot->whtInvoice
                        :  $pivot->whtInvoiceUsd;

                    $subtotal = $qty * $amount;
                    $total = $subtotal + $vat + $wht;

                    $pivot->subtotal = $subtotal;
                    $pivot->vat = $vat;
                    $pivot->wht = $wht;
                    $pivot->total = $total;

                    // Akumulasi untuk summary
                    $summary['subtotal'] += $subtotal;
                    $summary['vat'] += $vat;
                    $summary['wht'] += $wht;
                    $summary['total'] += $total;
                }
            } else {
                foreach ($invoice->transactions as $trx) {
                    $currency = strtoupper(trim($this->finalCurrency));

                    $qty = (int) $trx->quantity;
                    $rate = (float) ($trx->srate ?? 1);
                    // dd('Masuk ke bagian else - invoice normal', $invoice->status, $invoice->transactions->count());

                    // PERBAIKAN: Pastikan menggunakan field yang benar dari transaction
                    $amount = $currency === 'IDR'
                        ? (float) $trx->samountidr
                        : (float) $trx->sfcyamount;

                    $vat = $currency === 'IDR'
                        ? (float) ($trx->svatgstamount ?? 0)
                        : (float) ($trx->svatgstusd ?? 0);

                    $wht = $currency === 'IDR'
                        ? (float) ($trx->swhtaxamount ?? 0)
                        : (float) ($trx->shwtaxrateusd ?? 0);

                    // PERBAIKAN: Kalkulasi subtotal berdasarkan qty * rate per unit
                    // Bukan qty * amount (karena amount sudah total)
                    $unitPrice = $amount / $qty; // Hitung unit price
                    $subtotal = $qty * $unitPrice;

                    // Atau jika amount sudah merupakan subtotal:
                    // $subtotal = $amount;

                    $total = $subtotal + $vat + $wht;

                    // PERBAIKAN: Assign nilai ke transaction object agar bisa diakses di view
                    $trx->subtotal = $subtotal;
                    $trx->vat = $vat;
                    $trx->wht = $wht;
                    $trx->total = $total;

                    // Currency conversion jika diperlukan
                    if ($currency !== $this->finalCurrency) {
                        if ($currency === 'IDR' && $this->finalCurrency === 'USD') {
                            $cSub = $subtotal / $rate;
                            $cVat = $vat / $rate;
                            $cWht = $wht / $rate;
                        } elseif ($currency === 'USD' && $this->finalCurrency === 'IDR') {
                            $cSub = $subtotal * $rate;
                            $cVat = $vat * $rate;
                            $cWht = $wht * $rate;
                        } else {
                            $cSub = $subtotal;
                            $cVat = $vat;
                            $cWht = $wht;
                        }

                        // Update transaction object dengan nilai yang sudah dikonversi
                        $trx->subtotal = $cSub;
                        $trx->vat = $cVat;
                        $trx->wht = $cWht;
                        $trx->total = $cSub + $cVat + $cWht;
                    } else {
                        $cSub = $subtotal;
                        $cVat = $vat;
                        $cWht = $wht;
                    }

                    $summary['subtotal'] += $cSub;
                    $summary['vat'] += $cVat;
                    $summary['wht'] += $cWht;
                    $summary['total'] += ($cSub + $cVat + $cWht);
                }
            }

            // Format summary
            $formattedSummary = [
                'subtotal' => number_format(
                    $summary['subtotal'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'vat' => number_format(
                    $summary['vat'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'wht' => number_format(
                    $summary['wht'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'total' => number_format(
                    $summary['total'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
            ];

            // Render view - gunakan data yang konsisten
            $data = [
                'customer'             => $customer,
                'invoice'              => $invoice,
                'job'                  => $invoice->job,
                'container'            => $invoice->job->TjobContainer ?? collect(), // fallback jika null
                'transactions'         => $invoice->transactions,
                'totalPcs'             => $totalPcs,
                'totalgw'              => $totalgw,
                'formattedSummary'     => $formattedSummary,
                'finalCurrency'        => $this->finalCurrency,
                'invoice_number'       => $invoice->invoice_number,
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
                'type_invoice'   => 'SALES',
                'created_by'     => Auth::id(),
            ]);

            // Update transaksi dengan invoice_id
            Transaction::whereIn('id', $this->selectedTransactionIds)->update([
                'invoice_id' => $invoice->id
            ]);
            foreach ($selectedTransactions as $transaction) {
                $invoice->transactions()->attach($transaction->id, [
                    'amountInvoice' => $transaction->samountidr,
                    'amountInvoiceUsd' => $transaction->sfcyamount,
                    'quantityInvoice'   => $transaction->quantity,
                    'vatInvoice'        => $transaction->svatgstamount,
                    'vatInvoiceUsd'     => $transaction->svatgstusd,
                    'whtInvoice'        => $transaction->swhtaxamount,
                    'whtInvoiceUsd'     => $transaction->shwtaxrateusd,
                    'exchangeRate'      => $transaction->srate,
                    'remarks' => $transaction->description,
                ]);
            }
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
                        'invoice_id'           => $invoice->id,
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
                            'invoice_id'             => $invoice->id,
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
                            'invoice_id'             => $invoice->id,
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
                        'invoice_id'             => $invoice->id,
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
            $this->dispatch('showSuccessAlert', [
                'title' => 'Invoicing transaction!',
                'icon'  => 'success',
                'text'  => "Purchase Invoice #{$invoice->invoice_number} has been successfully invoiced.",
            ]);
            // session()->flash('message', 'Invoice created successfully!');
            // Optionally redirect or reset form here

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create invoice: ' . $e->getMessage());
        }
        $this->generateInvoiceNumber();

        $this->loadTransactions();
    }
    public function saveAsDraft()
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
                'due_date'       => null,
                'status'         => 'draft',
                'type_invoice'   => 'SALES',
                'currency'       => $this->currency ?? 'IDR',
                'total_amount'   => $grandTotal,
                'created_by'     => Auth::id(),
            ]);

            // Update transaksi dengan invoice_id
            Transaction::whereIn('id', $this->selectedTransactionIds)->update([
                'invoice_id' => $invoice->id
            ]);
            foreach ($selectedTransactions as $transaction) {
                $invoice->transactions()->attach($transaction->id, [
                    'amountInvoice' => $transaction->samountidr,
                    'amountInvoiceUsd' => $transaction->sfcyamount,
                    'quantityInvoice'   => $transaction->quantity,
                    'vatInvoice'        => $transaction->svatgstamount,
                    'vatInvoiceUsd'     => $transaction->svatgstusd,
                    'whtInvoice'        => $transaction->svatgstamount,
                    'whtInvoiceUsd'     => $transaction->shwtaxrateusd,
                    'remarks' => $transaction->description,
                ]);
            }
            DB::commit();
            session()->flash('message', 'Invoice created successfully!');
            // Optionally redirect or reset form here

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create invoice: ' . $e->getMessage());
        }
        $this->loadTransactions();
        $this->generateInvoiceNumber();
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

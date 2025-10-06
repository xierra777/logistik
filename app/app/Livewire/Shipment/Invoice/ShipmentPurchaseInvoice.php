<?php

namespace App\Livewire\Shipment\Invoice;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Transaction;
use App\Models\TShipments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Browsershot\Browsershot;

class ShipmentPurchaseInvoice extends Component
{
    public $shipmentId, $invoiceId, $isVoiding;
    public $shipment, $bank_id;
    public $customer, $void_reason, $selectedVendor, $bankReturnValue;
    public $id_shipment;
    public $transactions;
    public $selected_transactions = [];
    public array $selectedTransactionIds = [];
    public $invoice_number;
    public $currency, $invoicesIssued, $vendors;
    public $showExchangeRate = false; // Add this property
    public $finalCurrency = 'IDR'; // Add this property
    public $pdfData = '';
    public $selectAll = false;
    public $showModal = false;
    public $isIndeterminate = false, $voidReason_job_sale_invoice = false; // Tambahkan ini
    public $purchasingInvoice, $banks;


    public function mount($shipmentId)
    {
        $this->shipmentId = $shipmentId;

        if (empty($this->invoice_number)) {
            $this->invoice_number = $this->generateInvoiceNumber();
        }
        $this->banks = Bank::with('customer', 'accounts')->get();
        $this->shipment = TShipments::findOrFail($shipmentId);
        $this->vendors = Customer::select([
            'customers.id',
            'customers.name',
            'customers.email'
        ])
            ->join('transactions', 'customers.id', '=', 'transactions.cvendor')
            ->where('transactions.id_shipment', $shipmentId)
            ->whereNotNull('transactions.cvendor')
            ->distinct()
            ->get();
        $this->loadTransactions();
    }
    public function loadTransactions()
    {
        if ($this->selectedVendor) {
            $this->transactions = Transaction::where('id_shipment', $this->shipmentId)
                ->whereNull('purchasing_id')
                ->where('cvendor', $this->selectedVendor)
                ->get();

            if ($this->transactions->isEmpty()) {
                session()->flash('error', 'No transactions found for the selected vendor.');
            }
        } else {
            $this->transactions = collect();
            $this->purchasingInvoice = collect();
            // session()->flash('error', 'Please select a vendor to view transactions.');

            // Optional: Show all uninvoiced transactions for the shipment
            /*
            $this->transactions = Transaction::where('id_shipment', $this->shipmentId)
                ->whereNull('invoice_id')
                ->get();
            $this->purchasingInvoice = Invoice::where('shipment_id', $this->shipmentId)
                ->where('type_invoice', 'Purchasing')
                ->get();
            if ($this->transactions->isEmpty()) {
                session()->flash('error', 'No uninvoiced transactions found for this shipment.');
            }
            */
        }
        $this->selectedTransactionIds = [];
    }

    public function updatedSelectedVendor($value)
    {
        $this->loadTransactions();
        $this->updateIndeterminateState();
    }
    public function updatedSelectAll($value)
    {

        if ($value) {
            $this->selectedTransactionIds = $this->transactions->pluck('id')->toArray();
        } else {
            $this->selectedTransactionIds = [];
        }

        $this->updatedSelectedTransactionIds();
    }

    public function generateInvoiceNumber()
    {
        try {
            $prefix = "PI-BRN-" . now()->format('ym');

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
                'shipment.container',
                'bank' // atau 'shipment.jobContainer' sesuai nama relasi
            ])->findOrFail($invoiceId);

            // Akses langsung dari relasi yang sudah di-load
            $shipment = $invoice->shipment;
            $customer = $invoice->client;

            // Pastikan relasi ada sebelum mengakses
            if ($shipment && $shipment->container) {
                $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
                $totalgw = $shipment->container->sum('shipmentGrossWeight');
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
                    $currency = strtoupper(trim($invoice->currency));
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
                    $currency = strtoupper(trim($invoice->currency));

                    $qty = (int) $trx->quantity;
                    $rate = (float) ($trx->crate ?? 1);
                    // dd('Masuk ke bagian else - invoice normal', $invoice->status, $invoice->transactions->count());

                    // PERBAIKAN: Pastikan menggunakan field yang benar dari transaction
                    $amount = $currency === 'IDR'
                        ? (float) $trx->camountidr
                        : (float) $trx->cfcyamount;

                    $vat = $currency === 'IDR'
                        ? (float) ($trx->cvatgstamount ?? 0)
                        : (float) ($trx->cvatgstusd ?? 0);

                    $wht = $currency === 'IDR'
                        ? (float) ($trx->cwhtaxamount ?? 0)
                        : (float) ($trx->chwtaxrateusd ?? 0);

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
                    $this->finalCurrency === 'IDR' ? ',' : '.',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'total' => number_format(
                    $summary['total'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
            ];
            // Livewire / Controller
            $bank = BankAccount::with('bank.customer')
                ->where('bank_id', $invoice->bank_id)
                ->get();

            // $bankReturnValue = $bank->accounts;
            $data = [
                'customer'             => $customer,
                'invoice'              => $invoice,
                'shipment'             => $invoice->shipment,
                'container'            => $shipment->container ?? collect(), // fallback jika null
                'transactions'         => $invoice->transactions,
                'totalPcs'             => $totalPcs,
                'totalgw'              => $totalgw,
                'formattedSummary'     => $formattedSummary,
                'finalCurrency'        => $invoice->currency,
                'bank'                 => $bank,
                'invoice_number'       => $invoice->invoice_number,
                'showExchangeRate'     => $this->showExchangeRate,
            ];

            $html = view('livewire.shipment.invoice.shipment-purchase-invoice-pdf', $data)->render();

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
    #[On('issueInvoice')]
    public function issueInvoice($id)
    {
        // Ambil invoice berdasarkan $id
        $invoice = Invoice::findOrFail($id);

        // Ambil semua transaksi yang berelasi dengan invoice tersebut
        $transactions = Transaction::where('purchasing_id', $invoice->id)->get();

        // Update semua transaksi, bisa pakai query langsung:

        foreach ($transactions as $transaction) {
            $transaction->update(['is_purchasing' => true]);
        }
        $invoice->update([
            'status' => 'issued',
            'due_date' => now()->addDays(30),
            'updated_by' => Auth::user()->id,
        ]);


        $this->dispatch('showSuccessAlert', [
            'title' => 'Invoice Issued!',
            'text'  => "Purchase Invoice #{$invoice->invoice_number} has been marked as issued.",
            'icon'  => 'success'
        ]);
        $selectedTransactions = $invoice->transactions;
        foreach ($selectedTransactions as $transaction) {
            $costCoa = ChartOfAccount::find($transaction->coa_sale_id);

            // Jurnal Penjualan (Revenue)
            foreach ($selectedTransactions as $transaction) {
                $costCoa = ChartOfAccount::find($transaction->coa_cost_id);

                // Jurnal Penjualan (Revenue)
                if ($transaction->camountidr && $costCoa && $transaction->transactionVendor) {
                    $costAmount = $transaction->camountidr;
                    $vatAmount  = $transaction->cvatgstamount;
                    $whtAmount  = $transaction->cwhtaxamount;
                    $totalSale  = $costAmount + $vatAmount - $whtAmount;

                    // HUTANG (A/R) - Debit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $transaction->transactionVendor->coa_id,
                        'invoice_id'           => $invoice->id,
                        'debit'               => $totalSale,
                        'credit'              => 0,
                        'description'         => "HUTANG dari transaksi #{$transaction->transactionVendor->name} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
                            'invoice_id'          => $invoice->id,
                            'debit'               => 0,
                            'credit'              => $vatAmount,
                            'description'         => "PPN dari transaksi #{$transaction->shipment->shipment_id} - {$transaction->description}",
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
                            'description'         => "PPh 23 dari transaksi #{$transaction->shipment->shipment_id} - {$transaction->description}",
                            'transactionable_type' => get_class($transaction),
                            'transactionable_id'  => $transaction->id,
                            'date'                => now(),
                            'created_by'          => Auth::id(),
                        ]);
                    }

                    // Pendapatan (Revenue) - Kredit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $costCoa->id,
                        'invoice_id'             => $invoice->id,
                        'debit'               => 0,
                        'credit'              => $costAmount,
                        'description'         => "Sale transaction #{$transaction->reference_type} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
                        'transactionable_type' => $transaction->reference_type,
                        'transactionable_id'  => $transaction->id,
                        'date'                => now(),
                        'created_by'          => Auth::id(),
                    ]);
                }
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
        if (Schema::hasColumn('transactions', 'purchasing_id')) {
            Transaction::where('purchasing_id', $invoice->id)->update([
                'purchasing_id' => null,
                'is_purchasing' => null,
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
    public function previewPDF($invoiceId)
    {
        if ($invoiceId) {
            // Gunakan eager loading yang konsisten
            $invoice = Invoice::with([
                'transactions',
                'client',
                'shipment.container',
                'bank' // atau 'shipment.jobContainer' sesuai nama relasi
            ])->findOrFail($invoiceId);

            // Akses langsung dari relasi yang sudah di-load
            $shipment = $invoice->shipment;
            $customer = $invoice->client;

            // Pastikan relasi ada sebelum mengakses
            if ($shipment && $shipment->container) {
                $totalPcs = $shipment->container->sum('shipmentNoOfPackages');
                $totalgw = $shipment->container->sum('shipmentGrossWeight');
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
                    $currency = strtoupper(trim($invoice->currency));
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
                    $currency = strtoupper(trim($invoice->currency));

                    $qty = (int) $trx->quantity;
                    $rate = (float) ($trx->crate ?? 1);
                    // dd('Masuk ke bagian else - invoice normal', $invoice->status, $invoice->transactions->count());

                    // PERBAIKAN: Pastikan menggunakan field yang benar dari transaction
                    $amount = $currency === 'IDR'
                        ? (float) $trx->camountidr
                        : (float) $trx->cfcyamount;

                    $vat = $currency === 'IDR'
                        ? (float) ($trx->cvatgstamount ?? 0)
                        : (float) ($trx->cvatgstusd ?? 0);

                    $wht = $currency === 'IDR'
                        ? (float) ($trx->cwhtaxamount ?? 0)
                        : (float) ($trx->chwtaxrateusd ?? 0);

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
                    $this->finalCurrency === 'IDR' ? ',' : '.',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
                'total' => number_format(
                    $summary['total'],
                    $this->finalCurrency === 'USD' ? 2 : 0,
                    $this->finalCurrency === 'IDR' ? '.' : ',',
                    $this->finalCurrency === 'IDR' ? '.' : ','
                ),
            ];
            // Livewire / Controller
            $bank = BankAccount::with('bank.customer')
                ->where('bank_id', $invoice->bank_id)
                ->get();

            // $bankReturnValue = $bank->accounts;
            $data = [
                'customer'             => $customer,
                'invoice'              => $invoice,
                'shipment'             => $invoice->shipment,
                'container'            => $shipment->container ?? collect(), // fallback jika null
                'transactions'         => $invoice->transactions,
                'totalPcs'             => $totalPcs,
                'totalgw'              => $totalgw,
                'formattedSummary'     => $formattedSummary,
                'finalCurrency'        => $invoice->currency,
                'bank'                 => $bank,
                'invoice_number'       => $invoice->invoice_number,
                'showExchangeRate'     => $this->showExchangeRate,
            ];

            $html = view('livewire.shipment.invoice.shipment-purchase-invoice-pdf', $data)->render();

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
        if (!$this->bank_id) {
            DB::rollBack();
            session()->flash('error', 'Select a bank account to proceed.');
            return;
        }
        // dd($this->bank_id);
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
                ->whereNull('purchasing_id')
                ->get();

            if ($selectedTransactions->isEmpty()) {
                DB::rollBack();
                session()->flash('error', 'All selected transactions are already attached to an invoice.');
                return;
            }

            // Hitung subtotal, VAT, WHT, dan grand total
            $subtotal   = $selectedTransactions->sum('camountidr');
            $totalVat   = $selectedTransactions->sum('cvatgstamount');
            $totalWht   = $selectedTransactions->sum('cwhtaxamount');
            $grandTotal = $subtotal + $totalVat + $totalWht;
            // dd($this->selectedVendor);
            // Buat invoice baru
            $invoice = Invoice::create([
                'invoice_number'      => $this->invoice_number,
                'shipment_id'         => $this->shipmentId,
                'customer_id'         => $this->selectedVendor,
                'invoice_date'        => $this->invoice_date ?? now(),
                'due_date'            => now()->addDays(30),
                'status'              => 'issued',
                'currency'            => $this->finalCurrency ?? 'IDR',
                'total_amount'        => $grandTotal,
                'bank_id'             => $this->bank_id,
                'type_invoice'        => 'PURCHASING',
                'created_by'          => Auth::id(),
            ]);

            // Update transaksi dengan invoice_id
            Transaction::whereIn('id', $this->selectedTransactionIds)->update([
                'purchasing_id' => $invoice->id,
                'is_purchasing' => true,

            ]);
            foreach ($selectedTransactions as $transaction) {
                $invoice->transactions()->attach($transaction->id, [
                    'amountInvoice' => $transaction->camountidr,
                    'amountInvoiceUsd' => $transaction->cfcyamount,
                    'quantityInvoice'   => $transaction->quantity,
                    'vatInvoice'        => $transaction->cvatgstamount,
                    'vatInvoiceUsd'     => $transaction->cvatgstusd,
                    'whtInvoice'        => $transaction->cwhtaxamount,
                    'whtInvoiceUsd'     => $transaction->chwtaxrateusd,
                    'exchangeRate'      => $transaction->crate,
                    'remarks' => $transaction->description,
                ]);
            }
            // Buat jurnal untuk setiap transaksi
            foreach ($selectedTransactions as $transaction) {
                $costCoa = ChartOfAccount::find($transaction->coa_cost_id);

                // Jurnal Penjualan (Revenue)
                if ($transaction->camountidr && $costCoa && $transaction->transactionVendor) {
                    $costAmount = $transaction->camountidr;
                    $vatAmount  = $transaction->cvatgstamount;
                    $whtAmount  = $transaction->cwhtaxamount;
                    $totalSale  = $costAmount + $vatAmount - $whtAmount;

                    // HUTANG (A/R) - Debit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $transaction->transactionVendor->coa_id,
                        'invoice_id'           => $invoice->id,
                        'debit'               => $totalSale,
                        'credit'              => 0,
                        'description'         => "HUTANG dari transaksi #{$transaction->transactionVendor->name} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
                            'invoice_id'          => $invoice->id,
                            'debit'               => 0,
                            'credit'              => $vatAmount,
                            'description'         => "PPN dari transaksi #{$transaction->shipment->shipment_id} - {$transaction->description}",
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
                            'description'         => "PPh 23 dari transaksi #{$transaction->shipment->shipment_id} - {$transaction->description}",
                            'transactionable_type' => get_class($transaction),
                            'transactionable_id'  => $transaction->id,
                            'date'                => now(),
                            'created_by'          => Auth::id(),
                        ]);
                    }

                    // Pendapatan (Revenue) - Kredit
                    JournalEntry::create([
                        'transaction_id'      => $transaction->id,
                        'coa_id'              => $costCoa->id,
                        'invoice_id'             => $invoice->id,
                        'debit'               => 0,
                        'credit'              => $costAmount,
                        'description'         => "Sale transaction #{$transaction->reference_type} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
            ]);            // Optionally redirect or reset form here

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create invoice: ' . $e->getMessage());
        }
        $this->generateInvoiceNumber();

        $this->loadTransactions();
        $this->dispatch('transaction-updated');
    }
    public function saveAsDraft()
    {
        // Validasi minimal ada transaksi yang dipilih
        $this->validate([
            'selectedTransactionIds' => 'required|array|min:1',
            'invoice_number' => 'required|string|max:255',
            'selectedVendor'  => 'required'
        ], [
            'selectedTransactionIds.required' => 'Please select at least one transaction.',
            'selectedTransactionIds.min' => 'Please select at least one transaction.',
            'invoice_number.required' => 'Invoice number is required.',
        ]);

        DB::beginTransaction();

        try {
            // Ambil transaksi yang dipilih dan belum di-invoice
            $selectedTransactions = Transaction::whereIn('id', $this->selectedTransactionIds)
                ->whereNull('purchasing_id')
                ->get();

            if ($selectedTransactions->isEmpty()) {
                DB::rollBack();
                session()->flash('error', 'All selected transactions are already attached to an invoice.');
                return;
            }

            // Hitung subtotal, VAT, WHT, dan grand total
            $subtotal   = $selectedTransactions->sum('camountidr');
            $totalVat   = $selectedTransactions->sum('cvatgstamount');
            $totalWht   = $selectedTransactions->sum('cwhtaxamount');
            $grandTotal = $subtotal + $totalVat + $totalWht;
            // dd($this->shipmentId);
            // Buat invoice baru
            // dd($this->selectedVendor->id);
            $invoice = Invoice::create([
                'invoice_number' => $this->invoice_number,
                'shipment_id'         => $this->shipmentId,
                'customer_id'    => $this->selectedVendor,
                'invoice_date'   => $this->invoice_date ?? now(),
                'due_date'       => null,
                'status'         => 'draft',
                'type_invoice'   => 'PURCHASING',
                'currency'       => $this->finalCurrency ?? 'IDR',
                'total_amount'   => $grandTotal,
                'created_by'     => Auth::id(),
            ]);

            // Update transaksi dengan invoice_id
            Transaction::whereIn('id', $this->selectedTransactionIds)->update([
                'purchasing_id' => $invoice->id,
                'is_purchasing' => true,
            ]);
            foreach ($selectedTransactions as $transaction) {
                $invoice->transactions()->attach($transaction->id, [
                    'amountInvoice' => $transaction->camountidr,
                    'amountInvoiceUsd' => $transaction->cfcyamount,
                    'quantityInvoice'   => $transaction->quantity,
                    'vatInvoice'        => $transaction->cvatgstamount,
                    'vatInvoiceUsd'     => $transaction->cvatgstusd,
                    'whtInvoice'        => $transaction->cvatgstamount,
                    'whtInvoiceUsd'     => $transaction->chwtaxrateusd,
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
        $this->purchasingInvoice = Invoice::where('shipment_id', $this->shipmentId)->where('type_invoice', 'PURCHASING')->get();

        return view('livewire.shipment.invoice.shipment-purchase-invoice');
    }
}

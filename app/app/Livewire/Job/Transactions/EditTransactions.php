<?php

namespace App\Livewire\Job\Transactions;

use Livewire\Component;
use App\Models\TShipments;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ChargeSetting;
use App\Models\ChartOfAccount;
use App\Models\Invoice;
use App\Models\jobContainer;
use App\Models\JournalEntry;
use App\Models\TJob;
use App\Models\transaction\tax;

class EditTransactions extends Component
{
    public $chargeCoa;
    public $isReadonly = false;

    public $taxRates = [];
    public $taxData = [];
    public $taxRatesWht = [];

    public $jobId;
    public $job;
    public $customer_id;
    public $coaSaleId;
    public $coaCostId;
    public $is_invoiced;
    // === Charge Details ===
    public $charge, $description, $freight, $unit = 'CONTAINER', $ofdtype, $remarks;
    public $quantity = 0;

    // === Sale Details ===
    public $sclient, $scurrency, $srate = 0, $samount_qty = 0, $sincludedtax = "false";
    public $sfcyamount = 0, $samountidr = 0, $sdrcr, $svatgst = 0, $staxableamount = 0;
    public $svatgstamount = 0, $swhtaxrate, $swhtaxamount = 0, $sremarks, $sgrossprofit = 0;

    // === Cost Details ===
    public $cvendor, $creferenceno, $cdate, $cdrcr, $ccurrency, $crate = 0, $camount_qty = 0;
    public $cincludedtax = "No", $cfcyamount = 0, $camountidr = 0, $cvatgst, $cvatgstamount = 0;
    public $ctaxableamount, $cremarks, $cwhtaxrate, $cwhtaxamount = 0, $totalcost = 0;

    public $svatgstusd = 0, $cvatgstusd = 0;
    public $shwtaxrateusd = 0, $chwtaxrateusd = 0;
    public $vendors, $clients;
    public $shipmentType;
    public $transactionId, $transaction;

    public function mount($id = null, $transactionId = null)
    {
        $this->jobId = $id;
        $this->transactionId = $transactionId;

        if ($this->transactionId) {
            $this->loadTransaction($this->transactionId);
        }
        $this->transaction = Transaction::find($transactionId);

        $job = TJob::with(['client'])->find($id);

        if (!$job) {
            session()->flash('error', 'Job not found');
            return;
        }

        $this->clients = collect([
            $job->client ?? '',
        ])->filter()->unique();

        $taxes = Tax::where('is_active', '1')->get();

        $this->taxRates = $taxes->where('type', 'vat')->pluck('rate', 'id')->toArray();
        $this->taxRatesWht = $taxes->where('type', 'wht')->pluck('rate', 'id')->toArray();
        $this->taxData = $taxes->pluck('rate', 'id')->toArray();

        $this->chargeCoa = ChargeSetting::get();
        $this->vendors = Customer::where('category', 'creditor')->orderBy('name')->get();
        $this->checkReadonlyStatus();

        $this->updatedUnit($this->unit);
    }
    private function checkReadonlyStatus()
    {
        if ($this->transaction->invs && $this->transaction->invs->status === 'issued') {
            $this->isReadonly = true;
        }
    }


    public function loadTransaction($transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found');
            return;
        }

        // Sales Details
        $this->charge = $transaction->charge;
        $this->description = $transaction->description;
        $this->freight = $transaction->freight;
        $this->unit = $transaction->unit;
        $this->quantity = $transaction->quantity;
        $this->ofdtype = $transaction->ofdtype;
        $this->remarks = $transaction->remarks;

        $this->sclient = $transaction->sclient;
        $this->scurrency = $transaction->scurrency;
        $this->srate = $transaction->srate;
        $this->samount_qty = $transaction->samount_qty;
        $this->sincludedtax = $transaction->sincludedtax;
        $this->sfcyamount = $transaction->sfcyamount;
        $this->samountidr = $transaction->samountidr;
        $this->sdrcr = $transaction->sdrcr;
        $this->svatgst = $transaction->svatgst;
        $this->staxableamount = $transaction->staxableamount;
        $this->svatgstamount = $transaction->svatgstamount;
        $this->swhtaxrate = $transaction->swhtaxrate;
        $this->swhtaxamount = $transaction->swhtaxamount;
        $this->sremarks = $transaction->sremarks;
        $this->sgrossprofit = $transaction->sgrossprofit;

        // Cost Details
        $this->cvendor = $transaction->cvendor;
        $this->creferenceno = $transaction->creferenceno;
        $this->cdate = $transaction->cdate;
        $this->cdrcr = $transaction->cdrcr;
        $this->ccurrency = $transaction->ccurrency;
        $this->crate = $transaction->crate;
        $this->camount_qty = $transaction->camount_qty;
        $this->cincludedtax = $transaction->cincludedtax;
        $this->cfcyamount = $transaction->cfcyamount;
        $this->camountidr = $transaction->camountidr;
        $this->cvatgst = $transaction->cvatgst;
        $this->cvatgstusd = $transaction->cvatgstusd;
        $this->cvatgstamount = $transaction->cvatgstamount;
        $this->chwtaxrateusd = $transaction->chwtaxrateusd;
        $this->ctaxableamount = $transaction->ctaxableamount;
        $this->cremarks = $transaction->cremarks;
        $this->cwhtaxrate = $transaction->cwhtaxrate;
        $this->cwhtaxamount = $transaction->cwhtaxamount;
        $this->totalcost = $transaction->totalcost;

        // COA columns
        $this->coaSaleId = $transaction->coa_sale_id;
        $this->coaCostId = $transaction->coa_cost_id;
    }

    public function updatedUnit($value)
    {
        if (!$this->job) {
            $this->job = TJob::with('TjobContainer', 'shipments')->find($this->jobId);
        }

        switch ($value) {
            case 'CONTAINER':
                $this->quantity = $this->job->TjobContainer->count(); // atau hitung dari container relasi
                break;

            case 'PALLET':
                $this->quantity = 0; // method custom
                break;

            case 'DOCUMENT':
                $this->quantity =  $this->job->shipments->count();
                break;

            default:
                $this->quantity = 0;
        }
    }

    public function updatedCharge($value)
    {
        $charge = ChargeSetting::where('charge_code', $value)->first();

        if ($charge) {
            $this->coaSaleId = $charge->coa_sale_id;
            $this->coaCostId = $charge->coa_cost_id;
            $this->description = $charge->charge_name;

            // dd($this->coaSaleId, $this->coaCostId);
            // dd($this->description);
        } else {
            $this->coaSaleId = null;
            $this->coaCostId = null;
            $this->description = null;
        }
    }
    protected function rules()
    {
        return [
            // 'sclient' => Customer::exists('name'),
            // Add other validation rules as needed, for example:
            'charge' => 'required',
            'quantity' => 'required|numeric|min:1',
            // 'invoice_id' => 'required'
            // 'scurrency' => 'required',
            // etc.
        ];
    }

    // === Save Transaction ===
    public function save()
    {

        if ($this->transaction->invs && $this->transaction->invs->status === 'issued') {
            $this->dispatch('swal', [
                'title' => 'Gagal Update',
                'text' => 'Transaksi ini sudah masuk ke invoice yang telah issued.',
                'icon' => 'error', // Diubah dari 'errors' menjadi 'error'
            ]);
            $this->isReadonly = true;
            return;
        }
        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('scroll-to-error');
        }
        if ($this->svatgst == 0 || empty($this->svatgst)) {
            $this->svatgst = null;
        }
        if ($this->cvatgst == 0 || empty($this->cvatgst)) {
            $this->cvatgst = null;
        }
        if ($this->swhtaxrate == 0 || empty($this->swhtaxrate)) {
            $this->swhtaxrate = null;
        }
        if ($this->cwhtaxrate == 0 || empty($this->cwhtaxrate)) {
            $this->cwhtaxrate = null;
        }
        $transaction = Transaction::where('id', $this->transactionId)->update([
            'id_job' => $this->jobId,
            'charge' => $this->charge,
            'description' => $this->description,
            'freight' => $this->freight,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'ofdtype' => $this->ofdtype,
            'remarks' => $this->remarks,
            'coa_sale_id' => $this->coaSaleId,
            'coa_cost_id' => $this->coaCostId,
            // Sale
            'sclient' => $this->sclient,
            'scurrency' => $this->scurrency,
            'srate' => $this->srate,
            'samount_qty' => $this->samount_qty,
            'sincludedtax' => $this->sincludedtax,
            'sfcyamount' => $this->sfcyamount,
            'samountidr' => $this->samountidr,
            'sdrcr' => $this->sdrcr,
            'svatgst' => $this->svatgst,
            'staxableamount' => $this->staxableamount,
            'svatgstamount' => $this->svatgstamount,
            'swhtaxrate' => $this->swhtaxrate,
            'swhtaxamount' => $this->swhtaxamount,
            'sremarks' => $this->sremarks,
            'sgrossprofit' => $this->sgrossprofit,
            // Cost
            'cvendor' => $this->cvendor,
            'creferenceno' => $this->creferenceno,
            'cdate' => $this->cdate,
            'cdrcr' => $this->cdrcr,
            'ccurrency' => $this->ccurrency,
            'crate' => $this->crate,
            'camount_qty' => $this->camount_qty,
            'cincludedtax' => $this->cincludedtax,
            'cfcyamount' => $this->cfcyamount,
            'camountidr' => $this->camountidr,
            'cvatgst' => $this->cvatgst,
            'cvatgstamount' => $this->cvatgstamount,
            'ctaxableamount' => $this->ctaxableamount,
            'cremarks' => $this->cremarks,
            'cwhtaxrate' => $this->cwhtaxrate,
            'cwhtaxamount' => $this->cwhtaxamount,
            'svatgstusd' => $this->svatgstusd,
            'cvatgstusd' => $this->cvatgstusd,
            'shwtaxrateusd' => $this->shwtaxrateusd,
            'chwtaxrateusd' => $this->chwtaxrateusd,
            'reference_type' => 'JOB',
            'created_by' => Auth::id(),

        ]);

        // $this->createJournalEntries($transaction);
        $this->resetForm();
        $this->dispatch('transactionSaved');
        $this->chargeCoa = ChargeSetting::get();
        session()->flash('message', 'Transaksi berhasil disimpan!');
        $this->vendors = customer::where('category', 'creditor')->orderBy('name')->get();
        $this->dispatch('close-modal');
    }

    private function createJournalEntries($transaction)
    {
        $transaction->load('job');

        // Get COA for sale and cost
        $saleCoa = ChartOfAccount::find($transaction->coa_sale_id);
        $costCoa = ChartOfAccount::find($transaction->coa_cost_id);

        // Create sale journal entry
        if ($transaction->samountidr && $saleCoa) {
            $saleAmount = $transaction->samountidr;
            $vatAmount = $transaction->svatgstamount;
            $totalSale = $saleAmount + $vatAmount - $transaction->swhtaxamount;

            // Jurnal Piutang (A/R) - Debit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $transaction->transactionClient->coa_id, // COA A/R untuk customer
                'debit' => $totalSale,
                'credit' => 0,
                'description' => "Piutang dari transaksi #{$transaction->transactionClient->name} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => get_class($transaction),
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
            // Jurnal VAT (kalau ada)
            if ($vatAmount > 0 && $transaction->saleVat && $transaction->saleVat->coa_id) {
                JournalEntry::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $transaction->saleVat->coa_id, // VAT Output
                    'credit' => $vatAmount,
                    'debit' => 0,
                    'description' => "PPN dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                    'transactionable_type' => get_class($transaction),
                    'transactionable_id' => $transaction->id,
                    'date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            $whtAmount = $transaction->swhtaxamount;

            if ($whtAmount > 0 && $transaction->saleWht && $transaction->saleWht->coa_id) {
                JournalEntry::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $transaction->saleWht->coa_id, // COA WHT Receivable
                    'debit' => $whtAmount,
                    'credit' => 0,
                    'description' => "PPh 23 dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                    'transactionable_type' => get_class($transaction),
                    'transactionable_id' => $transaction->id,
                    'date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            // Jurnal Pendapatan (Revenue) - Kredit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $saleCoa->id,
                'credit' =>  $saleAmount,
                'description' => "Sale transaction #{$transaction->reference_type} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => $transaction->reference_type,
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        if ($transaction->camountidr && $costCoa) {
            $costAmount = $transaction->camountidr;
            $cvatAmount = $transaction->cvatgstamount;
            $totalCost = $costAmount + $cvatAmount - $transaction->cwhtaxamount;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $transaction->transactionVendor->coa_id, // COA A/R untuk customer
                'credit' => $totalCost,
                'description' => "Hutang dari transaksi #{$transaction->transactionVendor->name} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => get_class($transaction),
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
            // Jurnal VAT (kalau ada)
            if ($cvatAmount > 0 && $transaction->costVat && $transaction->costVat->coa_id) {
                JournalEntry::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $transaction->costVat->coa_id, // VAT Output
                    'debit' => $cvatAmount,
                    'description' => "PPN dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                    'transactionable_type' => get_class($transaction),
                    'transactionable_id' => $transaction->id,
                    'date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            $cwhtAmount = $transaction->cwhtaxamount;

            if ($cwhtAmount > 0 && $transaction->costWht && $transaction->costWht->coa_id) {
                JournalEntry::create([
                    'transaction_id' => $transaction->id,
                    'coa_id' => $transaction->costWht->coa_id, // COA WHT Receivable
                    'credit' => $cwhtAmount,
                    'description' => "PPh 23 dari transaksi #{$transaction->job->job_id} - {$transaction->description}",
                    'transactionable_type' => get_class($transaction),
                    'transactionable_id' => $transaction->id,
                    'date' => now(),
                    'created_by' => Auth::id(),
                ]);
            }

            // Jurnal Pendapatan (Expenses) - Kredit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $costCoa->id,
                'debit' =>  $costAmount,
                'description' => "Cost transaction #{$transaction->reference_type} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => $transaction->reference_type,
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
        }
    }

    private function resetForm()
    {
        $this->reset([
            'charge',
            'description',
            'freight',
            'unit',
            'ofdtype',
            'remarks',
            'quantity',
            'sclient',
            'scurrency',
            'srate',
            'samount_qty',
            'sincludedtax',
            'sfcyamount',
            'samountidr',
            'sdrcr',
            'svatgst',
            'staxableamount',
            'svatgstamount',
            'swhtaxrate',
            'swhtaxamount',
            'sremarks',
            'sgrossprofit',
            'cvendor',
            'creferenceno',
            'cdate',
            'cdrcr',
            'ccurrency',
            'crate',
            'camount_qty',
            'cincludedtax',
            'cfcyamount',
            'camountidr',
            'cvatgst',
            'cvatgstamount',
            'ctaxableamount',
            'cremarks',
            'cwhtaxrate',
            'cwhtaxamount',
            'svatgstusd',
            'cvatgstusd',
            'shwtaxrateusd',
            'chwtaxrateusd'
        ]);

        // Reload fresh data
        $this->chargeCoa = ChargeSetting::get();
        $this->vendors = Customer::where('category', 'creditor')->orderBy('name')->get();
    }

    public function closeModal()
    {
        $this->dispatch('close-modal');
    }
    public function render()
    {
        return view('livewire.job.transactions.edit-transactions');
    }
}

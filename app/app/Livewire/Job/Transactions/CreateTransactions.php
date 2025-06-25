<?php

namespace App\Livewire\Job\Transactions;

use App\Livewire\Shipment\ContainerShipment;
use Livewire\Component;
use App\Models\TShipments;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ChargeSetting;
use App\Models\ChartOfAccount;
use App\Models\jobContainer;
use App\Models\JournalEntry;
use App\Models\TJob;

class CreateTransactions extends Component
{
    public $chargeCoa;

    public $jobId;
    public $job;
    public $customer_id;
    public $coaSaleId;
    public $coaCostId;

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

    public function mount($id)
    {
        $this->jobId = $id;
        $job = TJob::with(['client'])->find($id);

        $this->clients = collect([
            $job->client,
        ])->filter()->unique();
        if (!$job) {
            throw new \Exception("Job with ID {$id} not found");
        }

        $customers = Customer::orderBy('name')->get();

        $this->chargeCoa = ChargeSetting::get();
        $this->updatedUnit($this->unit);

        $this->vendors = Customer::where('category', 'creditor')->orderBy('name')->get();
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
        } else {
            $this->coaSaleId = null;
            $this->coaCostId = null;
        }
    }

    // === Save Transaction ===
    public function save()
    {

        $transaction = Transaction::create([
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

        $this->createJournalEntries($transaction);
        $this->resetForm();
        $this->dispatch('transactionSaved');
        $this->dispatch('close-modal');
        $this->chargeCoa = ChargeSetting::get();
        session()->flash('message', 'Transaksi berhasil disimpan!');
        $this->vendors = customer::where('category', 'creditor')->orderBy('name')->get();
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
            $totalSale = $saleAmount * $transaction->quantity;

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

            // Jurnal Pendapatan (Revenue) - Kredit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $saleCoa->id,
                'debit' => $saleCoa->term_type === 'DR' ? $totalSale : 0,
                'credit' => $saleCoa->term_type === 'CR' ? $totalSale : 0,
                'description' => "Sale transaction #{$transaction->reference_type} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => $transaction->reference_type,
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        if ($transaction->camountidr && $costCoa) {
            $costAmount = $transaction->camountidr;
            $totalCost = $costAmount * $transaction->quantity;

            // Jurnal Hutang (A/P) - Kredit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $transaction->transactionVendor->coa_id, // COA A/P untuk vendor
                'debit' => 0,
                'credit' => $totalCost,
                'description' => "Hutang dari transaksi #{$transaction->transactionVendor->name} ({$transaction->job->job_id}) - {$transaction->description}",
                'transactionable_type' => get_class($transaction),
                'transactionable_id' => $transaction->id,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);

            // Jurnal Biaya (Expense) - Debit
            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $costCoa->id,
                'debit' => $costCoa->term_type === 'DR' ? $totalCost : 0,
                'credit' => $costCoa->term_type === 'CR' ? $totalCost : 0,
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
        return view('livewire.job.transactions.create-transactions');
    }

    // Update quantity based on containers associated with the job

}

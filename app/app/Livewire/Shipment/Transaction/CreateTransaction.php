<?php

namespace App\Livewire\Shipment\Transaction;

use App\Livewire\Shipment\ContainerShipment;
use Livewire\Component;
use App\Models\TShipments;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ChargeSetting;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\shipmentContainers;
use App\Models\transaction\tax;

class CreateTransaction extends Component
{
    public $chargeCoa;

    public $taxRates = [];
    public $taxData = [];
    public $taxRatesWht = [];

    public $shipmentId;
    public $shipment;
    public $customer_id;
    public $coaSaleId;
    public $coaCostId;

    // === Charge Details ===
    public $charge, $description, $freight, $unit = 'CONTAINER', $ofdtype, $remarks;
    public $quantity = 0;

    // === Sale Details ===
    public $sclient, $scurrency, $srate = 0, $samount_qty = 0, $sincludedtax = "No";
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
        $this->shipmentId = $id;
        $customers = customer::orderBy('name')->get();
        $shipment = TShipments::with([
            'client',
            'shipper',
            'consignee',
            'notify',
            'carrierModel',
            'deliveryAgent',
        ])->find($id);

        $this->clients = collect([
            $shipment->client,
            $shipment->shipper,
            $shipment->consignee,
            $shipment->notify,
            $shipment->carrierModel,
            $shipment->deliveryAgent,
        ])->filter()->unique();
        $taxes = tax::where('is_active', '1')->get(); // asumsikan ada kolom 'id', 'name', 'rate'

        $this->chargeCoa = ChargeSetting::get();
        $this->taxRates = $taxes->where('type', 'vat')->pluck('rate', 'id')->toArray(); // untuk dropdown
        $this->taxRatesWht = $taxes->where('type', 'wht')->pluck('rate', 'id')->toArray(); // untuk dropdown
        $this->taxData = $taxes->pluck('rate', 'id')->toArray(); // untuk Alpine.js
        $customers = Customer::orderBy('name')->get();
        $this->vendors = customer::where('category', 'creditor')->orderBy('name')->get();

        // $this->updateQty();
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
    public function updatedUnit($value)
    {
        if (!$this->shipment) {
            $this->shipment = TShipments::with('container')->find($this->shipmentId);
        }

        switch ($value) {
            case 'CONTAINER':
                $this->quantity = $this->shipment->container->count(); // atau hitung dari container relasi
                break;

            case 'PALLET':
                $this->quantity = 0; // method custom
                break;

            case 'DOCUMENT':
                $this->quantity =  $this->shipment->job();
                break;

            default:
                $this->quantity = 0;
        }
    }

    // === Simpan Data Transaksi Baru ===
    public function save()
    {
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
        $transaction = Transaction::create([
            'id_shipment' => $this->shipmentId,
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
            'reference_type' => 'SHIPMENT',
            'created_by' => Auth::id(),

        ]);


        // $this->createJournalEntries($transaction);
        $this->reset();
        $this->dispatch('transactionSaved');
        $this->dispatch('close-modal');
        $this->chargeCoa = ChargeSetting::get();
        session()->flash('message', 'Transaksi berhasil disimpan!');
        $this->vendors = customer::where('category', 'creditor')->orderBy('name')->get();
    }
    private function createJournalEntries($transaction)
    {
        $transaction->load('shipment');

        // Helper function to convert Indonesian formatted numbers to float
        $indoStringToFloat = function (string $value): float {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return floatval($value);
        };

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
                'description' => "Piutang dari transaksi #{$transaction->transactionClient->name} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
                'description' => "Sale transaction #{$transaction->reference_type} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
                'description' => "Hutang dari transaksi #{$transaction->transactionVendor->name} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
                'description' => "Cost transaction #{$transaction->reference_type} ({$transaction->shipment->shipment_id}) - {$transaction->description}",
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
    // public function loadClients()
    // {
    //     $this->clients = customer::where('category', 'DR')->orderBy('name')->get();
    // }

    public function closeModal()
    {
        // $this->resetFields();
        $this->dispatch('close-modal'); // untuk Alpine.js tutup modal
    }
    public function render()
    {
        return view('livewire.shipment.transaction.create-transaction');
    }
}

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

class CreateTransaction extends Component
{
    public $chargeCoa;

    public $shipmentId;
    public $shipment;
    public $customer_id;
    public $coaSaleId;
    public $coaCostId;

    // === Charge Details ===
    public $charge, $description, $freight, $unit, $ofdtype, $remarks;
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
        $this->chargeCoa = ChargeSetting::get();

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
    // === Simpan Data Transaksi Baru ===
    public function save()
    {

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
        ]);

        $this->createJournalEntries($transaction);
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

        // Validate sale amount and create sale journal entry
        if ($transaction->samountidr && $saleCoa) {
            $saleAmount = $transaction->samountidr;

            // Check if sale amount is 0 or negative
            if ($saleAmount <= 0) {
                return [
                    'success' => false,
                    'error' => 'Sale amount cannot be zero or negative',
                    'type' => 'sale_amount_invalid'
                ];
            }

            $totalSale = $saleAmount * $transaction->quantity;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $saleCoa->id,
                'debit' => $saleCoa->term_type === 'DR' ? $totalSale : 0,
                'credit' => $saleCoa->term_type === 'CR' ? $totalSale : 0,
                'description' => "Sale transaction #$transaction->reference_type ({$transaction->shipment->shipment_id}) - {$transaction->description}",
                'transactionable_type' => $transaction->reference_type,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        // Validate cost amount and create cost journal entry
        if ($transaction->camountidr && $costCoa) {
            $costAmount = $transaction->camountidr;

            // Check if cost amount is 0 or negative
            if ($costAmount <= 0) {
                return [
                    'success' => false,
                    'error' => 'Cost amount cannot be zero or negative',
                    'type' => 'cost_amount_invalid'
                ];
            }

            $totalCost = $costAmount * $transaction->quantity;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $costCoa->id, // Fixed: was using $saleCoa->id
                'debit' => $costCoa->term_type === 'DR' ? $totalCost : 0, // Fixed: was using $saleCoa and $totalSale
                'credit' => $costCoa->term_type === 'CR' ? $totalCost : 0, // Fixed: was using $saleCoa and $totalSale
                'description' => "Cost transaction #$transaction->reference_type ({$transaction->shipment->shipment_id}) - {$transaction->description}", // Fixed: was using $transaction->shipment instead of $transaction->shipment->shipment_id
                'transactionable_type' => $transaction->reference_type,
                'date' => now(),
                'created_by' => Auth::id(),
            ]);
        }

        // Return success if no errors
        return [
            'success' => true,
            'message' => 'Journal entries created successfully'
        ];
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

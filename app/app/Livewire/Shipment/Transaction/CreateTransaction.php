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
        $vendor = Customer::find($this->cvendor);
        $client = Customer::find($this->sclient);

        $transaction = Transaction::create([
            'shipment_id' => $this->shipmentId,
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
            'customer_id' => $client?->id,
            'sclient' => $client?->name,
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
            'vendor_id' => $vendor?->id,
            'cvendor' => $vendor?->name,
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
        ]);

        $saleCoa = ChartOfAccount::find($transaction->coa_sale_id);
        $costCoa = ChartOfAccount::find($transaction->coa_cost_id);
        // dd([
        //     'sale_term' => $saleCoa?->term_type,
        //     'cost_term' => $costCoa?->term_type,
        //     'sale_amount' => $transaction->samountidr,
        //     'cost_amount' => $transaction->camountidr,
        // ]);

        // === JURNAL SALE ===
        // Fungsi helper untuk konversi string Indo ke float
        $indoStringToFloat = function (string $value): float {
            // hapus titik ribuan, ganti koma jadi titik desimal
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
            return floatval($value);
        };

        // Ambil relasi coaSale dan coaCost dari ChargeSetting agar dapat akses term_type
        $chargeSetting = ChartOfAccount::find($transaction->coa_sale_id);
        $saleCoa = $chargeSetting?->coaSale;
        $costCoa = $chargeSetting?->coaCost;

        // JURNAL SALE
        if ($transaction->samountidr && $saleCoa) {
            $saleAmount = $indoStringToFloat($transaction->samountidr);
            $totalSale = $saleAmount * $transaction->quantity;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $saleCoa->id,
                'debit' => $saleCoa->term_type === 'DR' ? $totalSale : 0,
                'credit' => $saleCoa->term_type === 'CR' ? $totalSale : 0,
                'description' => "Sale transaction #{$transaction->id}",
                'date' => now(),
            ]);
        }

        // JURNAL COST
        if ($transaction->camountidr && $costCoa) {
            $costAmount = $indoStringToFloat($transaction->camountidr);
            $totalCost = $costAmount * $transaction->quantity;

            JournalEntry::create([
                'transaction_id' => $transaction->id,
                'coa_id' => $costCoa->id,
                'debit' => $costCoa->term_type === 'DR' ? $totalCost : 0,
                'credit' => $costCoa->term_type === 'CR' ? $totalCost : 0,
                'description' => "Cost transaction #{$transaction->id}",
                'date' => now(),
            ]);
        }


        // $this->loadClients(); 
        $this->dispatch('transactionSaved');
        $this->dispatch('close-modal');
        $this->reset();
        $this->chargeCoa = ChargeSetting::get();
        session()->flash('message', 'Transaksi berhasil disimpan!');
        $this->vendors = customer::where('category', 'creditor')->orderBy('name')->get();
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

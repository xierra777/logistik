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

class CreateTransaction extends Component
{
    public $chargeCoa;

    public $shipmentId;
    public $shipment;
    public $customer_id;

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

    protected $listeners = ['reloadTransactionData' => 'setShipmentId', 'handleReload'];

    public function setShipmentId($id)
    {
        $this->shipmentId = $id;
        $shipment = TShipments::find($id);
        $this->updateQty();
    }

    public function handleReload($payload)
    {
        if ($payload['id'] == $this->id) {
            $this->reset();
        }
    }


    // public function updateQty()
    // {
    //     $this->quantity = ContainerShipment::where('shipment_id', $this->id)->count();
    // }

    public function mount($id)
    {
        $this->shipmentId = $id;
        $customers = customer::select('id', 'name')->orderBy('name')->get();
        $shipment = TShipments::find($id);
        $this->chargeCoa = ChargeSetting::select('id', 'charge_code', 'charge_name')->get();

        $this->vendors = $customers->where('category', 'CR');;

        // $this->updateQty();
    }

    // === Simpan Data Transaksi Baru ===
    public function save()
    {
        // if (!$this->shipmentId) {
        //     session()->flash('error', 'Shipment ID tidak ditemukan!');
        //     return;
        // }
        // dd($this->charge);

        $vendor = Customer::find($this->cvendor);
        $client = Customer::find($this->sclient);

        Transaction::create([
            'shipment_id' => $this->shipmentId,
            'charge' => $this->charge,
            'description' => $this->description,
            'freight' => $this->freight,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'ofdtype' => $this->ofdtype,
            'remarks' => $this->remarks,
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

        $this->reset();
        // $this->loadClients(); 
        $this->dispatch('transactionSaved');
        $this->dispatch('close-modal');
        $this->chargeCoa = ChargeSetting::select('id', 'charge_code', 'charge_name')->get();

        session()->flash('message', 'Transaksi berhasil disimpan!');
    }

    // public function loadClients()
    // {
    //     $this->clients = customer::where('category', 'DR')->orderBy('name')->get();
    // }

    public function closeModal()
    {
        $this->resetFields();
        $this->dispatch('open = false'); // untuk Alpine.js tutup modal
    }
    public function render()
    {
        return view('livewire.shipment.transaction.create-transaction');
    }
}

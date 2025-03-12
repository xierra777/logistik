<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\transaction;
use App\Models\Customer;
use Illuminate\Foundation\Exceptions\Renderer\Listener;

class Tranksaksi extends Component
{

    public $shipmentId;
    public $customer_id; // Tambahkan properti ini

    // === Bagian Charge ===
    public $charge;
    public $description;
    public $freight;
    public $unit;
    public $quantity;
    public $ofdtype;
    public $remarks;

    // === Bagian Sale ===
    public $sclient;
    public $scurrency;
    public $srate = "0";            // Exchange Rate untuk sale
    public $samount_qty = "0";      // Jumlah (Qty) untuk sale (perhitungan)
    public $sincludedtax = "No";
    public $sfcyamount = "0";       // Amount per unit (FCY) untuk sale
    public $samountidr = "0";       // Hasil perhitungan sale (IDR)
    public $sdrcr;
    public $svatgst = "0";
    public $staxableamount = "0";
    public $svatgstamount = "0";
    public $swhtaxrate;
    public $swhtaxamount = "0";
    public $sremarks;
    public $sgrossprofit = "0";     // Gross Profit (sale - cost)

    // === Bagian Cost ===
    public $cvendor;
    public $creferenceno;
    public $cdate;
    public $cdrcr;
    public $ccurrency;
    public $crate = "0";            // Exchange Rate untuk cost
    public $camount_qty = "0";      // Jumlah (Qty) untuk cost (perhitungan)
    public $cincludedtax = "No";
    public $cfcyamount = "0";       // Amount per unit (FCY) untuk cost
    public $camountidr = "0";       // Hasil perhitungan cost (IDR)
    public $cvatgst;
    public $cvatgstamount = "0";
    public $ctaxableamount;
    public $cremarks;
    public $cwhtaxrate;
    public $cwhtaxamount = "0";

    public $vendor_id;
    public $client_id;
    public $vendors;
    public $clients;
    protected $listeners = ['transactionSaved' => 'loadTransactions', 'reloadTransactionData' => 'setShipmentId'];

    public function setShipmentId($shipmentId)
    {
        $this->shipmentId = $shipmentId;
    }
    public function mount($shipmentId)
    {
        $this->shipmentId = $shipmentId;
        $customers = Customer::orderBy('name')->get();

        // Filter customers berdasarkan kategori
        $this->vendors = $customers->where('category', 'CR');
        $this->clients = $customers->where('category', 'DR');
    }

    public function save()
    {

        if (!$this->shipmentId) {
            session()->flash('error', 'Shipment ID tidak ditemukan!');
            return;
        }
        // Cari data vendor & client berdasarkan ID
        $vendor = Customer::find($this->cvendor);
        $client = Customer::find($this->sclient);

        // Simpan ke database
        transaction::create([
            'shipment_id' => $this->shipmentId,
            'charge' => $this->charge,
            'description' => $this->description,
            'freight' => $this->freight,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'ofdtype' => $this->ofdtype,
            'remarks' => $this->remarks,

            // Sale
            'sclient' => $client ? $client->name : null,
            'customer_id'   => $client ? $client->id : null,
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
            'cvendor' => $vendor ? $vendor->name : null,
            'vendor_id'     => $vendor ? $vendor->id : null,
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


        ]);
        // dd($client);


        $this->reset();
        $this->dispatch('transactionSaved');
        $this->dispatch('close-modal');
        $this->dispatch('updateTransactions');
        $this->resetExcept('shipmentId');
        $this->shipmentId = request()->query('shipmentId', $this->shipmentId) ?? $this->shipmentId;

        session()->flash('message', 'Transaksi berhasil disimpan!');
    }
    public function render()
    {
        $customers = Customer::orderBy('name')->get();
        $vendors = $customers->where('category', 'CR');
        $clients = $customers->where('category', 'DR');

        return view('livewire.accounting.tranksaksi', [
            'clients' => $clients,
            'vendors' => $vendors,
        ]);
    }
}

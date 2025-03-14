<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Customer;

class Tranksaksi extends Component
{
    public $shipmentId;
    public $customer_id;

    // === Charge Details ===
    public $charge, $description, $freight, $unit, $quantity, $ofdtype, $remarks;

    // === Sale Details ===
    public $sclient, $scurrency, $srate = 0, $samount_qty = 0, $sincludedtax = "No";
    public $sfcyamount = 0, $samountidr = 0, $sdrcr, $svatgst = 0, $staxableamount = 0;
    public $svatgstamount = 0, $swhtaxrate, $swhtaxamount = 0, $sremarks, $sgrossprofit = 0;

    // === Cost Details ===
    public $cvendor, $creferenceno, $cdate, $cdrcr, $ccurrency, $crate = 0, $camount_qty = 0;
    public $cincludedtax = "No", $cfcyamount = 0, $camountidr = 0, $cvatgst, $cvatgstamount = 0;
    public $ctaxableamount, $cremarks, $cwhtaxrate, $cwhtaxamount = 0;

    public $vendor_id, $client_id;
    public $vendors, $clients;

    protected $listeners = ['reloadTransactionData' => 'setShipmentId'];

    // === Set Shipment ID dari Parent ===
    public function setShipmentId($shipmentId)
    {
        $this->shipmentId = $shipmentId;
    }

    // === Load Clients ===
    public function loadClients()
    {
        $this->clients = Customer::where('category', 'DR')->get();
    }

    // === Lifecycle Hook ===
    public function mount($shipmentId)
    {
        // dd($shipmentId); // Cek apakah ini selalu ada setelah refresh

        $this->shipmentId = $shipmentId;
        $customers = Customer::orderBy('name')->get();

        // Filter customers berdasarkan kategori
        $this->vendors = $customers->where('category', 'CR');
        $this->clients = $customers->where('category', 'DR');
    }

    // === Simpan Data Transaksi ===
    public function save()
    {
        if (!$this->shipmentId) {
            session()->flash('error', 'Shipment ID tidak ditemukan!');
            return;
        }

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
            'sclient' => $client?->name,
            'customer_id' => $client?->id,
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
            'cvendor' => $vendor?->name,
            'vendor_id' => $vendor?->id,
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

        $this->reset(); // Reset form setelah save
        $this->loadClients(); // Refresh data client
        $this->dispatch('transactionSaved')->to('App\Livewire\ViewShipments'); // Refresh parent
        $this->dispatch('close-modal'); // Tutup modal

        session()->flash('message', 'Transaksi berhasil disimpan!');
    }

    // === Render Component ===
    public function render()
    {
        return view('livewire.accounting.tranksaksi', [
            'clients' => $this->clients,
            'vendors' => $this->vendors,
        ]);
    }
}

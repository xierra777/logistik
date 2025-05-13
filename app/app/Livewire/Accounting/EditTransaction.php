<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Container;
use App\Models\Shipment;
use phpDocumentor\Reflection\Types\This;

class EditTransaction extends Component
{
    public $shipmentId;
    public $transactionId;
    public $customers_id;
    public $isEditing = false;
    public $sigma;

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
    public $ctaxableamount, $cremarks, $cwhtaxrate, $cwhtaxamount = 0;

    public $clients, $vendors;

    protected $listeners = ['loadTransaction' => 'getTransactionId'];

    public function mount($shipmentId = null, $transactionId = null)
    {
        $this->shipmentId = $shipmentId;
        $this->transactionId = $transactionId;
        $sigma = Shipment::find($shipmentId);
        $this->sigma = $sigma->where('id', $shipmentId)->first();
        $customers = Customer::orderBy('name')->get();
        $this->vendors = $customers->where('category', 'CR');
        $this->clients = $customers->where('category', 'DR');

        $this->updateQty();

        if ($this->transactionId) {
            $this->loadTransaction($this->transactionId);
            $this->isEditing = true;
        }
    }

    public function getTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
        $this->isEditing = true;
        $this->loadTransaction($transactionId);
    }

    public function updateQty()
    {
        $this->quantity = Container::where('shipment_id', $this->shipmentId)->count();
    }

    public function resetFields()
    {
        $this->transactionId = null;
        $this->charge = $this->description = $this->freight = $this->unit = $this->ofdtype = $this->remarks = null;
        $this->quantity = 0;

        $this->sclient = $this->scurrency = $this->sdrcr = $this->sremarks = null;
        $this->srate = $this->samount_qty = $this->sfcyamount = $this->samountidr = 0;
        $this->sincludedtax = "No";
        $this->svatgst = $this->staxableamount = $this->svatgstamount = $this->swhtaxrate = $this->swhtaxamount = $this->sgrossprofit = 0;

        $this->cvendor = $this->creferenceno = $this->cdate = $this->cdrcr = $this->ccurrency = $this->cremarks = null;
        $this->crate = $this->camount_qty = $this->cfcyamount = $this->camountidr = 0;
        $this->cincludedtax = "No";
        $this->cvatgst = $this->cvatgstamount = $this->ctaxableamount = $this->cwhtaxrate = $this->cwhtaxamount = 0;
    }

    public function closeModal()
    {
        $this->resetFields();
        $this->isEditing = false;
        $this->dispatch('closeModal'); // untuk Alpine.js tutup modal
    }

    public function loadTransaction($transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) return;

        $this->charge = $transaction->charge;
        $this->description = $transaction->description;
        $this->freight = $transaction->freight;
        $this->unit = $transaction->unit;
        $this->quantity = $transaction->quantity;
        $this->ofdtype = $transaction->ofdtype;
        $this->remarks = $transaction->remarks;

        $this->sclient = $transaction->customer_id;
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
    }

    public function save()
    {
        if (!$this->shipmentId) {
            session()->flash('error', 'Shipment ID tidak ditemukan!');
            return;
        }

        $vendor = Customer::find($this->cvendor);
        $client = Customer::find($this->sclient);

        Transaction::where('id', $this->transactionId)->update([
            'shipment_id' => $this->shipmentId,
            'charge' => $this->charge,
            'description' => $this->description,
            'freight' => $this->freight,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'ofdtype' => $this->ofdtype,
            'remarks' => $this->remarks,
            'customer_id' => $client?->id,
            'sclient' => $client?->name,
            'cvendor' => $vendor?->name,
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

        // Debugging all variables
        // dd(get_object_vars($this));
        $this->dispatch('transactionSaved')->to('App\Livewire\ViewShipments');
        $this->closeModal();
        session()->flash('message', 'Transaksi berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.accounting.edit-transaction');
    }
}

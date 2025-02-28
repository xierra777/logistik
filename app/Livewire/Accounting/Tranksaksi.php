<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\transaction;

class Tranksaksi extends Component
{
    // === Bagian Charge ===
    public $charge;
    public $description;
    public $freight;
    public $unit;
    public $quantity = "0";
    public $ofdtype;
    public $remarks;

    // === Bagian Sale ===
    public $sclient;
    public $scurrency;
    public $srate = "0";            // Exchange Rate untuk sale
    public $samount_qty = "0";      // Jumlah (Qty) untuk sale (perhitungan)
    public $sincludedtax;
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
    public $cincludedtax;
    public $cfcyamount = "0";       // Amount per unit (FCY) untuk cost
    public $camountidr = "0";       // Hasil perhitungan cost (IDR)
    public $cvatgst;
    public $cvatgstamount = "0";
    public $ctaxableamount;
    public $cremarks;
    public $cwhtaxrate;
    public $cwhtaxamount = "0";

    // === Update hooks untuk perhitungan Sale ===
    public function updatedSamount_qty($value)
    {
        $this->recalcTotals();
    }
    public function updatedSfcyamount($value)
    {
        $this->recalcTotals();
    }
    public function updatedSrate($value)
    {
        $this->recalcTotals();
    }

    // === Update hooks untuk perhitungan Cost ===
    public function updatedCamount_qty($value)
    {
        $this->recalcTotals();
    }
    public function updatedCfcyamount($value)
    {
        $this->recalcTotals();
    }
    public function updatedCrate($value)
    {
        $this->recalcTotals();
    }

    /**
     * recalcTotals() menghitung:
     * - Sale Amount (samountidr) = samount_qty x sfcyamount x srate
     * - Cost Amount (camountidr) = camount_qty x cfcyamount x crate
     * - Gross Profit (sgrossprofit) = Sale Amount - Cost Amount
     */
    public function recalcTotals()
    {
        // Perhitungan Sale
        $saleQty     = floatval($this->samount_qty);
        $salePerUnit = floatval($this->sfcyamount);
        $saleExRate  = floatval($this->srate);
        $computedSale = $saleQty * $salePerUnit * $saleExRate;
        $this->samountidr = (string)$computedSale;

        // Perhitungan Cost
        $costQty     = floatval($this->camount_qty);
        $costPerUnit = floatval($this->cfcyamount);
        $costExRate  = floatval($this->crate);
        $computedCost = $costQty * $costPerUnit * $costExRate;
        $this->camountidr = (string)$computedCost;

        // Gross Profit
        $this->sgrossprofit = (string)($computedSale - $computedCost);
    }

    /**
     * Method submit() akan:
     * 1. Memvalidasi data.
     * 2. Menyimpan data ke database.
     * 3. Menampilkan pesan sukses.
     * 4. Mereset form.
     */
    public function save()
    {

        // Simpan data ke database
        transaction::create([
            // Bagian Charge
            'charge'       => $this->charge,
            'description'  => $this->description,
            'freight'      => $this->freight,
            'unit'         => $this->unit,
            'quantity'     => $this->quantity,
            'ofdtype'      => $this->ofdtype,
            'remarks'      => $this->remarks,

            // Bagian Sale
            'sclient'         => $this->sclient,
            'scurrency'       => $this->scurrency,
            'srate'           => $this->srate,
            'samount_qty'     => $this->samount_qty,
            'sincludedtax'    => $this->sincludedtax,
            'sfcyamount'      => $this->sfcyamount,
            'samountidr'      => $this->samountidr,
            'sdrcr'           => $this->sdrcr,
            'svatgst'         => $this->svatgst,
            'staxableamount'  => $this->staxableamount,
            'svatgstamount'   => $this->svatgstamount,
            'swhtaxrate'      => $this->swhtaxrate,
            'swhtaxamount'    => $this->swhtaxamount,
            'sremarks'        => $this->sremarks,
            'sgrossprofit'    => $this->sgrossprofit,

            // Bagian Cost
            'cvendor'         => $this->cvendor,
            'creferenceno'    => $this->creferenceno,
            'cdate'           => $this->cdate,
            'cdrcr'           => $this->cdrcr,
            'ccurrency'       => $this->ccurrency,
            'crate'           => $this->crate,
            'camount_qty'     => $this->camount_qty,
            'cincludedtax'    => $this->cincludedtax,
            'cfcyamount'      => $this->cfcyamount,
            'camountidr'      => $this->camountidr,
            'cvatgst'         => $this->cvatgst,
            'cvatgstamount'   => $this->cvatgstamount,
            'ctaxableamount'  => $this->ctaxableamount,
            'cremarks'        => $this->cremarks,
            'cwhtaxrate'      => $this->cwhtaxrate,
            'cwhtaxamount'    => $this->cwhtaxamount,
        ]);
        dd(get_object_vars($this));

        session()->flash('message', 'Transaction saved successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.accounting.tranksaksi');
    }
}

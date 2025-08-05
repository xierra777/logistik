<?php

namespace App\Models;

use App\Models\transaction\tax;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{   
    //  use softDeletes;

    protected $table = 'transactions';

    protected $fillable = [
        'id_shipment',
        'id_job',
        'reference_type',
        'invoice_id',
        // Charge section
        'charge',
        'description',
        'freight',
        'unit',
        'quantity',
        'ofdtype',
        'remarks',
        // Sale section
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
        'svatgstusd',
        'swhtaxrate',
        'shwtaxrateusd',
        'swhtaxamount',
        'sremarks',
        'sgrossprofit',
        // Cost section
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
        'cvatgstusd',
        'cvatgstamount',
        'chwtaxrateusd',
        'ctaxableamount',
        'cremarks',
        'cwhtaxrate',
        'cwhtaxamount',
        'totalcost',
        'is_invoiced',
        // COA columns
        'coa_sale_id',
        'coa_cost_id',
        'created_by',
        'updated_by',
    ];
    public function scopeHasAmount($query)
    {
        return $query->where(function ($q) {
            $q->where('samountidr', '>', 0)->whereNotNull('sclient');
        })->orWhere(function ($q) {
            $q->where('camountidr', '>', 0)->whereNotNull('cvendor');
        });
    }
    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class, 'transaction_id');
    }
    public function coaSale()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_sale_id');
    }
    public function coaCost()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_cost_id');
    }
    public function transactionClient()
    {
        return $this->belongsTo(Customer::class, 'sclient');
    }
    public function saleVat()
    {
        return $this->belongsTo(tax::class, 'svatgst');
    }
    public function costVat()
    {
        return $this->belongsTo(tax::class, 'cvatgst');
    }
    public function saleWht()
    {
        return $this->belongsTo(tax::class, 'swhtaxrate');
    }
    public function costWht()
    {
        return $this->belongsTo(tax::class,  'cwhtaxrate');
    }
    public function shipment()
    {
        return $this->belongsTo(TShipments::class, 'id_shipment');
    }
    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }

    public function transactionVendor()
    {
        return $this->belongsTo(Customer::class, 'cvendor');
    }

    public function invs()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'invoice_transaction')
            ->withPivot('amountInvoice', 'amountInvoiceUsd', 'quantityInvoice', 'vatInvoice', 'vatInvoiceUsd', 'whtInvoice', 'whtInvoiceUsd', 'remarks')
            ->withTimestamps();
    }

    public function getSamountgpFormattedAttribute()
    {
        $samountidr = is_numeric($this->samountidr) ? $this->samountidr : 0;
        $camountidr = is_numeric($this->camountidr) ? $this->camountidr : 0;
        return number_format(($samountidr - $camountidr), 2, ',', '.');
    }
}

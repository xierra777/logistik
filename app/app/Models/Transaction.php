<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'shipment_id',
        'customer_id',  // Tambahkan field ini
        'vendor_id',  // Tambahkan field ini
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
        // COA columns
        'coa_sale_id',
        'coa_cost_id'
    ];

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
    public function customer()
    {
        return $this->belongsTo(customer::class, 'customer_id');
    }
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Customer::class, 'vendor_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function getGpAttribute()
    {
        $toFloat = fn($v) => floatval(str_replace(',', '.', str_replace('.', '', $v)));
        return $toFloat($this->samountidr) * $toFloat($this->quantity) - $toFloat($this->camountidr) * $toFloat($this->quantity);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'shipment_id',

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
        'swhtaxrate',
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
        'cvatgstamount',
        'ctaxableamount',
        'cremarks',
        'cwhtaxrate',
        'cwhtaxamount',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}

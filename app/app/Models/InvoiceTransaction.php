<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
// use Illuminate\Database\Eloquent\SoftDeletes;


class InvoiceTransaction extends Pivot
{
    protected $table = 'invoice_transaction';
    // use softDeletes;

    protected $fillable = [
        'amountInvoice',
        'amountInvoiceUsd',
        'quantityInvoice',
        'exchangeRate',
        'vatInvoice',
        'vatInvoiceUsd',
        'whtInvoice',
        'whtInvoiceUsd',
        'remarks'
    ];

    public $timestamps = true;

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

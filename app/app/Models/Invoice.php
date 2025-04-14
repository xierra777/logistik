<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'shipment_id',
        'customer_id',
        'invoice_date',
        'currency',
        'sub_total',
        'total_vat',
        'total_wht',
        'grand_total',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class);
    }
    public function transactions()
    {
        return $this->belongsTo(transaction::class);
    }
}

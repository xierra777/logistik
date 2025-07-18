<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = ['customerVendor_id', 'payment_no', 'payment_date', 'amount', 'currency', 'exchange_rate', 'notes'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentJobAllocations::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

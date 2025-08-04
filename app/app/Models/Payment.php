<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = ['customerVendor_id', 'status', 'payment_no', 'date', 'bank_coa', 'amount', 'currency', 'exchange_rate', 'remarks', 'refrence_type', 'refrence_id', 'journal_posted_at', 'created_by', 'updated_by'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerVendor_id');
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'refrence_id');
    }
}

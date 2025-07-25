<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentAllocation extends Model
{
    use SoftDeletes;

    protected $fillable = ['payment_id', 'invoice_id', 'amount_allocated', 'job_id', 'shipment_id', 'allocated_amount', 'currency', 'exchange_rate', 'remarks'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function invoices()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function job()
    {
        return $this->belongsTo(TJob::class);
    }
}

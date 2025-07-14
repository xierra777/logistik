<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentJobAllocations extends Model
{
    protected $fillable = ['payment_id', 'job_id', 'allocated_amount'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function job()
    {
        return $this->belongsTo(TJob::class);
    }
}

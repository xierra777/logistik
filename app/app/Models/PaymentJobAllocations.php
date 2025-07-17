<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentJobAllocations extends Model
{
    use SoftDeletes;

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

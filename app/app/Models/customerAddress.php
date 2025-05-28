<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customerAddress extends Model
{



    protected $fillable = [
        'address',
        'customer_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}

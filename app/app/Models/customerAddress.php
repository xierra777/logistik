<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class customerAddress extends Model
{



    protected $fillable = [
        'address',
        'customer_id'
    ];

    public function costumer()
    {
        return $this->belongsTo(Customer::class, 'costumer_id');
    }
}

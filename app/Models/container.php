<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class container extends Model
{
    protected $fillable = [
        'shipment_id',
        'container_id',
        'container_type',
    ];

    public function shipment()
    {
        return $this->belongsTo(shipments::class, 'shipment_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Shipment;

class Container extends Model
{
    protected $fillable = ['shipment_id', 'container_id', 'pcs', 'unit', 'container_type', 'container_seal', 'pack_type', 'gross_weight', 'measurement', 'volume_weight', 'chargeable_weight'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}

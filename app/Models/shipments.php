<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipments extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'container_id',
        'container_type',
        'shipper',
        'consignee',
        'notify',
        'ocean_vessel_feeder',
        'ocean_vessel_mother',
        'port_of_discharge',
        'combined_transport',
        'port_of_loading',
        'packages',
        'description',
        'gross_weight',
        'measurement',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

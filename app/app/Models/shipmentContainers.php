<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class shipmentContainers extends Model
{
    protected $casts = ['containersData' => 'array'];

    protected $fillable = [
        'id_jobContainer',
        'id_shipments',
        'containersData',
    ];

    public function shipment()
    {
        return $this->belongsTo(TShipments::class, 'id_shipments');
    }
    public function jobContainer()
    {
        return $this->belongsTo(jobContainer::class, 'id_jobContainer');
    }
}

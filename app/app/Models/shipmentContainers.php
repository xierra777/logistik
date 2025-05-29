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
    public function getShipmentNoOfPackagesAttribute()
    {
        return isset($this->containersData['shipmentNoOfPackages'])
            ? (int)$this->containersData['shipmentNoOfPackages']
            : 0;
    }
    public function getShipmentGrossWeightAttribute()
    {
        return isset($this->containersData['shipmentGrossWeight'])
            ? (int)$this->containersData['shipmentGrossWeight']
            : 0;
    }
}

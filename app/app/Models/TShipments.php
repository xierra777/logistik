<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TShipments extends Model
{
    protected $casts = [
        'dataShipments' => 'array',
        'dataContainers' => 'array',
    ];

    protected $fillable = [
        'id_job',
        'shipmentsTypeJob',
        'shipment_id',
        'shipmentClient_id',
        'shipmentShipper_id',
        'shipmentConsignee_id',
        'shipmentNotify_id',
        'employee_id',
        'carrier',
        'dataShipments',
    ];
    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }
    public function container()
    {
        return $this->hasMany(shipmentContainers::class, 'id_shipments');
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'shipmentClient_id');
    }
    public function carrierModel()
    {
        return $this->belongsTo(Customer::class, 'carrier');
    }
    public function employee()
    {
        return $this->belongsTo(user::class, 'employee_id');
    }

    public function ogents()
    {
        return $this->belongsTo(Customer::class, 'ogentsJob');
    }
    public function dagents()
    {
        return $this->belongsTo(Customer::class, 'dagentsJob');
    }
    public function shipper()
    {
        return $this->belongsTo(Customer::class, 'shipmentShipper_id');
    }
    public function consignee()
    {
        return $this->belongsTo(Customer::class, 'shipmentConsignee_id');
    }
    public function notify()
    {
        return $this->belongsTo(Customer::class, 'shipmentNotify_id');
    }
}

<?php

namespace App\Models;

use App\Models\transactions\costTransactions;
use App\Models\transactions\salesTransactions;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class TShipments extends Model
{  
    //   use softDeletes;

    protected $casts = [
        'dataShipments' => 'array',
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
        'shipmentClient_address',
        'shipmentCarrierAirline',
        'shipmentContainerDeliveryAgent',
        'shipmentCarrierAgent',
        'shipmentDeliveryAgent',
        'carrier',
        'dataShipments',
        'created_by',
        'updated_by',
    ];
    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }
    public function container()
    {
        return $this->hasMany(shipmentContainers::class, 'id_shipments');
    }
    public function shipmentTransaction()
    {
        return $this->hasMany(Transaction::class, 'id_shipment');
    }
    public function client()
    {
        return $this->belongsTo(Customer::class, 'shipmentClient_id');
    }
    public function carrierModel()
    {
        return $this->belongsTo(Customer::class, 'shipmentCarrierAirline');
    }

    public function carrierAgent()
    {
        return $this->belongsTo(Customer::class, 'shipmentCarrierAgent');
    }
    public function deliveryAgent()
    {
        return $this->belongsTo(Customer::class, 'shipmentDeliveryAgent');
    }

    public function containerShipmentCarrier()
    {
        return $this->belongsTo(Customer::class, 'containerShipmentCarrierAirline');
    }
    public function containerDeliveryAgent()
    {
        return $this->belongsTo(Customer::class, 'shipmentContainerDeliveryAgent');
    }
    public function employee()
    {
        return $this->belongsTo(user::class, 'employee_id');
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

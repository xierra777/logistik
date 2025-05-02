<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'shipment_no',
        'liners',
        'servicesType',
        'jobtType',
        'shipper_id',
        'consignee_id',
        'notify_id',
        'estimearrival',
        'estimedelivery',
        'ocean_vessel_feeder',
        'ocean_vessel_mother',
        'port_of_discharge',
        'place_of_receipt',
        'port_of_loading',
        'description',
    ];

    public function shipper()
    {
        return $this->belongsTo(Customer::class, 'shipper_id');
    }

    public function consignee()
    {
        return $this->belongsTo(Customer::class, 'consignee_id');
    }
    public function notify()
    {
        return $this->belongsTo(Customer::class, 'notify_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'shipment_id');
    }
    public function containers()
    {
        return $this->hasMany(Container::class, 'shipment_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}

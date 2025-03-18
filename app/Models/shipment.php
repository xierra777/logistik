<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'shipment_no',
        'shipper',
        'consignee',
        'notify',
        'estimearrival',
        'estimedelivery',
        'ocean_vessel_feeder',
        'ocean_vessel_mother',
        'port_of_discharge',
        'place_of_receipt',
        'port_of_loading',
        'description',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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

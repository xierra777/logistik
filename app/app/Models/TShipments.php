<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TShipments extends Model
{
    protected $casts = ['dataShipments' => 'array'];

    protected $fillable = [
        'id_job',
        'shipmentsTypeJob',
        'shipper_id',
        'consignee_id',
        'notify_id',
        'carrier',
        'dataShipments',
    ];

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
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

}
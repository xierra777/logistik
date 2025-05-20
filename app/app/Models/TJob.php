<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TJob extends Model
{
    protected $casts = ['data' => 'array'];

    protected $fillable = [
        'job_id',
        'type_job',
        'client_id',
        'ogentsJob',
        'dagentsJob',
        'data',
    ];

    public function TjobContainer()
    {
        return $this->hasMany(jobContainer::class, 'id_job');
    }
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
    public function getCarrierModelAttribute()
    {
        return Customer::find($this->data['carrier'] ?? null);
    }
}

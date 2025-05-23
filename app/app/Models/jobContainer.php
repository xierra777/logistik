<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jobContainer extends Model
{
    protected $casts = ['containers' => 'array'];

    protected $fillable = [
        'id_job',
        'containers'
    ];

    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }
    public function shipment()
    {
        return $this->hasMany(shipmentContainers::class, 'id_jobContainer');
    }
}

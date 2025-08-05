<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class jobContainer extends Model
{
    protected $casts = ['containers' => 'array'];
    // use softDeletes;

    protected $fillable = [
        'id_job',
        'containers',
        'created_by',
        'updated_by',
    ];

    public function job()
    {
        return $this->belongsTo(TJob::class, 'id_job');
    }
    public function shipment()
    {
        return $this->hasMany(shipmentContainers::class, 'id_jobContainer');
    }
    public function getNoOfPackagesAttribute()
    {
        return isset($this->containers['noOfPackages'])
            ? (int)$this->containers['noOfPackages']
            : 0;
    }
    public function getGrossWeightAttribute()
    {
        return isset($this->containers['grossWeight'])
            ? (int)$this->containers['grossWeight']
            : 0;
    }
}

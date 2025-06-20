<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TJob extends Model
{
    protected $casts = ['data' => 'array'];

    protected $fillable = [
        'id_job',
        'jobBillLadingNo',
        'jobBillLadingDate',
        'houseJobBillLadingNo',
        'houseJobBillLadingDate',
        'type_job',
        'carrierAirline',
        'employee_id',
        'client_id',
        'ogentsJob',
        'dagentsJob',
        'customerCodeJob',
        'data',
        'created_by',
        'updated_by',
    ];

    public function shipments()
    {
        return $this->hasMany(TShipments::class, 'id_job');
    }
    public function TjobContainer()
    {
        return $this->hasMany(jobContainer::class, 'id_job');
    }
    public function jobTransactions()
    {
        return $this->hasMany(Transaction::class, 'id_job');
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
    public function carrierModel()
    {
        return $this->belongsTo(Customer::class, 'carrierAirline');
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}

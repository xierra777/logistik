<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class TJob extends Model
{

    // use softDeletes;

    protected $casts = ['data' => 'array'];

    protected $fillable = [
        'job_id',
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
    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class, 'job_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, PaymentAllocation::class, 'job_id', 'id', 'id', 'payment_id');
    }


    public function getOutstandingDebtAttribute()
    {
        $totalInvoice = $this->total_amount;
        $totalPayments = $this->total_payments ?? 0;
        return max($totalInvoice - $totalPayments, 0);
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

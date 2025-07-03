<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'shipment_id',
        'job_id',
        'customer_id',
        'invoice_date',
        'due_date',
        'currency',
        'total_amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function shipment()
    {
        return $this->belongsTo(TShipments::class);
    }
    public function job()
    {
        return $this->belongsTo(TJob::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

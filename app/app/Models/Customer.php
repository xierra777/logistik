<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    // use softDeletes;

    protected $fillable = [
        'name',
        'customer_code',
        'country',
        'address',
        'contact',
        'web',
        'email',
        'roles',
        'coa_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
    public function getOutstandingAmountAttribute()
    {
        $totalJobsAmount = $this->jobs()->sum('total_amount'); // total amount dari semua job customer
        $totalPayments = $this->payments()->sum('amount');
        return max(0, $totalJobsAmount - $totalPayments);
    }
    public function getCategoryAttribute()
    {
        return $this->relationLoaded('chartOfAccount') && $this->chartOfAccount
            ? $this->chartOfAccount->term_type
            : 'unknown';
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }
    public function jobs()
    {
        return $this->hasMany(TJob::class, 'client_id');
    }
    public function addresses()
    {
        return $this->hasMany(customerAddress::class, 'customer_id');
    }
    public function shipmentsAsClient()
    {
        return $this->hasMany(TShipments::class, 'shipmentClient_id');
    }

    public function shipmentsAsShipper()
    {
        return $this->hasMany(TShipments::class, 'shipmentShipper_id');
    }

    public function shipmentsAsConsignee()
    {
        return $this->hasMany(TShipments::class, 'shipmentConsignee_id');
    }

    public function shipmentsAsNotify()
    {
        return $this->hasMany(TShipments::class, 'shipmentNotify_id');
    }
}

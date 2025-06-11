<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

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

    public function getCategoryAttribute()
    {
        return $this->relationLoaded('chartOfAccount') && $this->chartOfAccount
            ? $this->chartOfAccount->term_type
            : 'unknown';
    }

    public function addresses()
    {
        return $this->hasMany(customerAddress::class, 'customer_id');
    }
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

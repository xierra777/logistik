<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'address',
        'contact',
        'web',
        'email',
        'roles',
        'coa_id'
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    // public function chartOfAccount()
    // {
    //     return $this->belongsTo(ChartOfAccount::class);
    // }

    // public function getCategoryAttribute()
    // {
    //     return $this->coa ? $this->coa->term_type : 'unknown';
    // }


    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

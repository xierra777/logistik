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
        'roles',
        'address',
        'contact',
        'web',
        'email'
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

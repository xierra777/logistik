<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChartOfAccount;

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
        'email',
        'coa_id'
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    // Relasi ke Chart of Accounts
    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }

    // Misalnya, jika kamu ingin menentukan kategori customer secara dinamis berdasarkan data COA:
    public function getCategoryAttribute()
    {
        return $this->coa ? $this->coa->term_type : 'unknown';
    }


    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

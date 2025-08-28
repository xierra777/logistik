<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'bank_name',
        'customer_id',
        'created_by',
        'updated_by',
    ];

    // RELATION: Bank belongs to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // RELATION: Bank has many BankAccounts
    public function accounts()
    {
        return $this->hasMany(BankAccount::class, 'bank_id');
    }
}

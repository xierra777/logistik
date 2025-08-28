<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_id',
        'bank_code',
        'branch_name',
        'swift_code',
        'currency',
        'bank_account_number',
        'bank_coa_id',
        'created_by',
        'updated_by',
    ];

    // RELATION: BankAccount belongs to Bank
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    // RELATION: BankAccount belongs to ChartOfAccount
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'bank_coa_id');
    }
}

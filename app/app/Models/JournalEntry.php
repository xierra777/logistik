<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'coa_id',
        'debit',
        'credit',
        'description',
        'date',
        'created_by',
        'updated_by',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class JournalEntry extends Model
{
    use HasFactory;
    // use softDeletes;

    protected $fillable = [
        'transaction_id',
        'invoice_id',
        'coa_id',
        'debit',
        'transactionable_type',
        'credit',
        'description',
        'date',
        'reversal_of',
        'is_reversal',
        'description_of_reversal',
        'created_by',
        'updated_by',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

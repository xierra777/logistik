<?php

namespace App\Models\transaction;

use App\Models\ChartOfAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class tax extends Model
{
    protected $fillable = ['name', 'type', 'rate', 'context', 'coa_id', 'is_active', 'created_by', 'updated_by'];
    public function coaAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

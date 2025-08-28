<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class ChartOfAccount extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $fillable = [
        'account_code',
        'account_name',
        'term_type',
        'account_type',
        'is_payment',
        'parent_account_id',
        'created_by',
        'updated_by',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'coa_id');
    }
    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_account_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_account_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}

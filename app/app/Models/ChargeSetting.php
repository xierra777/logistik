<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeSetting extends Model
{
    protected $fillable = [
        'charge_code',
        'charge_name',
        'coa_sale_id',
        'coa_cost_id',
        'created_by',
        'updated_by',
    ];

    public function coaSale()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_sale_id');
    }

    public function coaCost()
    {
        return $this->belongsTo(ChartOfAccount::class, 'coa_cost_id');
    }
}

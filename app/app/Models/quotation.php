<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation extends Model
{
    protected $fillable = [
        'quotation_no',
        'quotation_date',
        'customer_id',
        'sell_currency',
        'sell_exchange_rate',
        'buy_currency',
        'buy_exchange_rate',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'sell_exchange_rate' => 'decimal:6',
        'buy_exchange_rate' => 'decimal:6',
    ];



    public function getTotalSellIdrAttribute(): float
    {
        return $this->sellItems->sum('amount_idr');
    }

    public function getTotalCostIdrAttribute(): float
    {
        return $this->costItems->sum('amount_idr');
    }

    public function getGrossProfitIdrAttribute(): float
    {
        return $this->total_sell_idr - $this->total_cost_idr;
    }
}

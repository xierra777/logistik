<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class quotation_items extends Model
{
    protected $fillable = [
        'quotation_id',
        'type',
        'charge',
        'amount',
        'amount_idr',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_idr' => 'decimal:2',
    ];


    public function calculateAmountIdr(): float
    {
        $rate = $this->type === 'SELL'
            ? $this->quotation->sell_exchange_rate
            : $this->quotation->buy_exchange_rate;

        return $this->amount * $rate;
    }
}

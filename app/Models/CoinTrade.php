<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coin_id',
        'type',
        'price',
        'quantity'
    ];
    
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}

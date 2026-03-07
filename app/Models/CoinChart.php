<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinChart extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'coin_id',
        'open_price',
        'close_price',
        'high_price',
        'low_price'
    ];
}

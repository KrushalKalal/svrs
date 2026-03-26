<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldCoinTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'remark',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
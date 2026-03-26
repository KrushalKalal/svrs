<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoldCoinWallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_earned',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(GoldCoinTransaction::class, 'user_id', 'user_id');
    }
}
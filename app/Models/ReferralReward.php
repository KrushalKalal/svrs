<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    protected $fillable = [
        'earner_id',
        'from_user_id',
        'level',
        'coin_id',
        'percentage',
        'base_quantity',
        'reward_quantity',
        'status',
    ];

    protected $casts = [
        'percentage' => 'decimal:4',
        'base_quantity' => 'decimal:8',
        'reward_quantity' => 'decimal:8',
    ];

    public function earner()
    {
        return $this->belongsTo(User::class, 'earner_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}
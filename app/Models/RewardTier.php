<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardTier extends Model
{
    protected $fillable = [
        'name',
        'g_coins',
        'required_referrals',
        'tier_order',
        'status',
    ];

    protected $casts = [
        'g_coins' => 'decimal:2',
    ];

    public function claims()
    {
        return $this->hasMany(UserRewardClaim::class);
    }
}
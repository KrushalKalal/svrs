<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRewardClaim extends Model
{
    protected $fillable = [
        'user_id',
        'reward_tier_id',
        'g_coins_claimed',
        'referral_count_at_claim',
        'claim_number',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'g_coins_claimed' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Rejected',
            1 => 'Approved',
            2 => 'Pending',
            default => 'Unknown'
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(RewardTier::class, 'reward_tier_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'admin';

    protected $fillable = [
        'member_code',
        'sponsor_id',
        'role',
        'status',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'password',
        'amount',
        'coin_price',
        'attachment',
        'profile_image',
        'otp',
        'is_verified',
        'fcm_token',
        'is_refer_member',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'amount' => 'decimal:2',
        'password' => 'hashed',
        'is_refer_member' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id', 'member_code');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'sponsor_id', 'member_code');
    }
    public function bankDetail()
    {
        return $this->hasOne(BankDetail::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function coinTrades()
    {
        return $this->hasMany(CoinTrade::class);
    }

    public function membership()
    {
        return $this->hasOne(MemberMembership::class);
    }

    public function referralRewardsEarned()
    {
        return $this->hasMany(ReferralReward::class, 'earner_id');
    }

    public function referralRewardsGiven()
    {
        return $this->hasMany(ReferralReward::class, 'from_user_id');
    }

    public function activeReferMembers()
    {
        return $this->hasMany(User::class, 'sponsor_id', 'member_code')
            ->where('status', 1)
            ->where('is_refer_member', 1);
    }

    // All direct referrals regardless of status
    public function allDirectReferrals()
    {
        return $this->hasMany(User::class, 'sponsor_id', 'member_code');
    }

    public function goldCoinWallet()
    {
        return $this->hasOne(GoldCoinWallet::class);
    }

    public function goldCoinTransactions()
    {
        return $this->hasMany(GoldCoinTransaction::class);
    }

    public function rewardClaims()
    {
        return $this->hasMany(UserRewardClaim::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is member
    public function isMember()
    {
        return $this->role === 'member';
    }

    // Check if user is an approved refer member
    public function isReferMember()
    {
        return $this->is_refer_member == 1;
    }

    // Get total coin balance (buy + reward - sell)
    public function getTotalCoinBalanceAttribute()
    {
        $buy = $this->coinTrades()->whereIn('type', ['buy', 'reward'])->sum('quantity');
        $sell = $this->coinTrades()->where('type', 'sell')->sum('quantity');
        return $buy - $sell;
    }

    // Get active refer member count for milestone check
    public function getActiveReferMemberCountAttribute()
    {
        return $this->activeReferMembers()->count();
    }

}

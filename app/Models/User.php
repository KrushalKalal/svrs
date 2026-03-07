<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
        'fcm_token'
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
}

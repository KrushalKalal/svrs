<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberMembership extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'refer_code',
        'refer_link',
        'payment_screenshot',
        'amount',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // status labels
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

    public function plan()
    {
        return $this->belongsTo(MembershipPlan::class, 'plan_id');
    }
}
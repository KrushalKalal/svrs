<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = ['name', 'price', 'status'];

    public function memberships()
    {
        return $this->hasMany(MemberMembership::class, 'plan_id');
    }
}
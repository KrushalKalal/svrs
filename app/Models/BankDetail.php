<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
        'user_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'branch_name',
        'upi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

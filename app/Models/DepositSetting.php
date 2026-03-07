<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositSetting extends Model
{
    use HasFactory;

    protected $table = 'deposit_settings';

    protected $fillable = [
        'min_amount',
        'max_amount',
    ];
    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


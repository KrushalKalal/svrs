<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalSetting extends Model
{
    use HasFactory;
    protected $fillable = ['min_amount', 'max_amount'];
}

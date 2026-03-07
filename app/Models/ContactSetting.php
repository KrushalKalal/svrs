<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'bank',
        'account_name',
        'account_number',
        'ifsc_code',
        'branch',
        'qr_image',
    ];
}

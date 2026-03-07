<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    use HasFactory;

    protected $table = 'customer_support';

    protected $fillable = [
        'user_id',
        'message',
        'attachment',
        'reply',
        'replied_at',
    ];
    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

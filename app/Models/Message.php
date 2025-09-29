<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'reply',    // optional
        'reply_to', // optional link to other message
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

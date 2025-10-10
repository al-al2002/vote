<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'conversation_id',
        'message',
        'image',
        'status',
        'to',
        'sender_type',
    ];

    /**
     * 🔗 Each message belongs to a user (sender)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🕓 Get the latest message in a conversation
     */
    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'conversation_id', 'conversation_id')->latestOfMany();
    }

    /**
     * 📦 Scope for a specific conversation
     */
    public function scopeInConversation($query, $conversation_id)
    {
        return $query->where('conversation_id', $conversation_id);
    }

    /**
     * 📦 Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    /**
     * 🖼 Full image URL accessor
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}

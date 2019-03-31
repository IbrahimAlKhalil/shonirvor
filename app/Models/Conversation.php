<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function members()
    {
        return $this->hasMany(ConversationMember::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(ChatMessage::class, ConversationMember::class);
    }
}

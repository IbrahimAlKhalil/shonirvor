<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationMember extends Model
{
    public function conversation() {
        return $this->belongsTo(Conversation::class);
    }

    public function messages() {
        return $this->hasMany(ChatMessage::class);
    }
}

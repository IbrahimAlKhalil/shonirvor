<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    public function type() {
        return $this->belongsTo(ChatMessageType::class, 'id', 'type_id');
    }

    public function author() {
        return $this->belongsTo(ConversationMember::class);
    }
}

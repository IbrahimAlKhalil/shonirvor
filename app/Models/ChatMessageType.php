<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessageType extends Model
{
    public function messages() {
        return $this->hasMany(ChatMessage::class);
    }
}

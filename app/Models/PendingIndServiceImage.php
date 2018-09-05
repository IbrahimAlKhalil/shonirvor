<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingIndServiceImage extends Model
{
    public function registration()
    {
        return $this->belongsTo(PendingIndService::class);
    }
}

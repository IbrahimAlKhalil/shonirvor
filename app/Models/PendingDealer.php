<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingDealer extends Model
{
    public function documents()
    {
        return $this->hasMany(PendingDealerDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

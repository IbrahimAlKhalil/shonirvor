<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingDealerDocument extends Model
{
    public function dealer()
    {
        return $this->belongsTo(PendingDealer::class);
    }
}

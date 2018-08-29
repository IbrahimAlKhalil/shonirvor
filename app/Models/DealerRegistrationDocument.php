<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerRegistrationDocument extends Model
{
    public function registration()
    {
        return $this->belongsTo(DealerRegistration::class);
    }
}

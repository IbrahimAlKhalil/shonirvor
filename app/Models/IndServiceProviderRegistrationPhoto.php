<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndServiceProviderRegistrationPhoto extends Model
{
    public function registration()
    {
        return $this->belongsTo(IndServiceProviderRegistration::class);
    }
}

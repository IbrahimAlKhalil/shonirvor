<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgServiceProviderRegistrationPhoto extends Model
{
    public function registration()
    {
        return $this->belongsTo(OrgServiceProviderRegistration::class);
    }
}

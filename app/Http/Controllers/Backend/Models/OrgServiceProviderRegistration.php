<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgServiceProviderRegistration extends Model
{
    public function photos()
    {
        return $this->hasMany(OrgServiceProviderRegistrationPhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

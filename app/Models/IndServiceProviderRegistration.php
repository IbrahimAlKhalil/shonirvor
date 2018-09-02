<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndServiceProviderRegistration extends Model
{
    public function photos()
    {
        return $this->hasMany(IndServiceProviderRegistrationPhoto::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

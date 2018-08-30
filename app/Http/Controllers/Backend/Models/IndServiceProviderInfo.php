<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndServiceProviderInfo extends Model
{
    public function photos() {
        return $this->hasMany(ServiceProviderRegistrationPhoto::class);
    }
}

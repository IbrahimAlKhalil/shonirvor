<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealerRegistration extends Model
{
    public function documents()
    {
        return $this->hasMany(DealerRegistrationDocument::class);
    }
}

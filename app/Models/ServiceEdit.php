<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEdit extends Model
{
    public function serviceEditable()
    {
        return $this->morphTo();
    }
}

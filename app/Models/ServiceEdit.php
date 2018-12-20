<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEdit extends Model
{
    protected $casts = ['data' => 'array'];

    public function serviceEditable()
    {
        return $this->morphTo();
    }
}

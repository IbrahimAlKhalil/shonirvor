<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slug extends Model
{
    protected $fillable = ['name'];

    public function sluggable()
    {
        return $this->morphTo();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndService extends Model
{
    public function images()
    {
        return $this->hasMany(IndServiceImage::class);
    }

    public function docs()
    {
        return $this->hasMany(IndServiceDoc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

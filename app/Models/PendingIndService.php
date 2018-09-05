<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingIndService extends Model
{
    public function images()
    {
        return $this->hasMany(PendingIndServiceImage::class);
    }

    public function docs()
    {
        return $this->hasMany(PendingIndServiceDoc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

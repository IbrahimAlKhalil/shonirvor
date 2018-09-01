<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrgService extends Model
{
    public function images()
    {
        return $this->hasMany(OrgServiceImage::class);
    }

    public function docs()
    {
        return $this->hasMany(OrgServiceDoc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

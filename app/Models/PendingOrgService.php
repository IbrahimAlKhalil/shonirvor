<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingOrgService extends Model
{
    public function images()
    {
        return $this->hasMany(PendingOrgServiceImage::class);
    }

    public function docs()
    {
        return $this->hasMany(PendingOrgServiceDoc::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

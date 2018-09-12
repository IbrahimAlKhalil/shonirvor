<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

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

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function thana()
    {
        return $this->belongsTo(Thana::class);
    }

    public function union()
    {
        return $this->belongsTo(Union::class);
    }

    public function categories()
    {
        return $this->morphMany(Category::class, 'categoriable');
    }
}

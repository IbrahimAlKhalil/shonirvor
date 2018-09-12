<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

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

    public function workMethods()
    {
        return $this->belongsToMany(WorkMethod::class, 'work_method_pending_ind_service');
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

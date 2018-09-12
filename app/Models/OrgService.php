<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrgService extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndService extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }
}
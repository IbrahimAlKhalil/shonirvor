<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Org extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workMethods() {
        return $this->belongsToMany(WorkMethod::class);
    }


    public function workImages() {
        return $this->morphMany(WorkImages::class, 'work_imagable');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function subCategories() {
        return $this->morphToMany(SubCategory::class, 'sub_categoriable');
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }
}

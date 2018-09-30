<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class Ind extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workMethods()
    {
        return $this->belongsToMany(WorkMethod::class)->withPivot('rate');
    }

    public function workImages()
    {
        return $this->morphMany(WorkImages::class, 'work_imagable');
    }

    public function edit()
    {
        return $this->morphOne(ServiceEdit::class, 'service_editable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param $status string
     * @return object
     **/
    public function subCategories($status = null)
    {
        $result = $this->morphToMany(SubCategory::class, 'sub_categoriable');

        switch ($status) {
            case 'confirmed':
                $result = $result->where('is_confirmed', '=', 1);
                break;
            case 'requested':
                $result = $result->where('is_confirmed', '=', 0);
        }

        return $result;
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
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

    /**
     * @param $status string
     * @return object|null
     **/
    public static function getOnly($status)
    {
        $result = null;

        switch ($status) {
            case 'pending':
                $result = Ind::where('is_pending', 1);
                break;
            case 'approved':
                $result = Ind::where('is_pending', 0);
        }

        return $result;
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }
}

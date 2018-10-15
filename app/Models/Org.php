<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class Org extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    /*********************/
    /***** Relations *****/
    /*********************/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workMethods()
    {
        return $this->belongsToMany(WorkMethod::class);
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

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function feedbacks()
    {
        return $this->morphMany(Feedback::class, 'feedbackable');
    }

    public function subCategories($status = null)
    {
        $result = $this->morphToMany(SubCategory::class, 'sub_categoriable');

        switch ($status) {
            case 'confirmed':
                $result = $result->where('is_confirmed', 1);
                break;
            case 'requested':
                $result = $result->where('is_confirmed', 0);
        }

        return $result;
    }

    function subCategoryRates()
    {
        return $this->belongsToMany(SubCategory::class, 'org_sub_category_rates')->withPivot('rate');
    }


    /*******************/
    /***** Scopes *****/
    /*******************/

    public function scopeOnlyApproved($query)
    {
        return $query->where('is_pending', 0);
    }

    public function scopeOnlyPending($query)
    {
        return $query->where('is_pending', 1);
    }

    public function scopeOnlyTop($query)
    {
        return $query->where('is_top', 1);
    }

    /**
     * Add this method after select() method
     */
    public function scopeWithFeedbacksAvg($query)
    {
        return $query->leftJoin('feedbacks', function ($join) {
            $join->on('orgs.id', 'feedbacks.feedbackable_id')
                ->where('feedbackable_type', 'org');
        })
            ->addSelect(DB::raw('orgs.id, avg(star) as feedbacks_avg'))
            ->groupBy('id');
    }
}

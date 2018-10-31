<?php

namespace App\Models;

use \Illuminate\Database\Query\Builder as Builder;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Illuminate\Database\Eloquent\Model;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Ind
 *
 * @method static Builder exceptExpired()
 * @method static Builder onlyExpired()
 *
 * */

class Ind extends Model
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
        return $this->belongsToMany(WorkMethod::class)->withPivot(['sub_category_id', 'rate']);
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

    public function payments()
    {
        return $this->morphMany(Income::class, 'incomeable');
    }

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

    public function referredBy()
    {
        return $this->belongsTo(Reference::class, 'id', 'service_id')
            ->where('service_type_id', 1);
    }


    /******************/
    /***** Scopes *****/
    /******************/

    public function scopeOnlyApproved($query)
    {
        return $query->whereNotNull('expire');
    }

    public function scopeOnlyPending($query)
    {
        return $query->whereNull('expire');
    }

    public function scopeOnlyTop($query)
    {
        return $query->whereNotNull('top_expire');
    }

    public function scopeOnlyExpired($query)
    {
        $query->where('expire', '<', Date('Y-m-d H:i:s'));
    }

    public function scopeExceptExpired($query)
    {
        $query->where('expire', '>=', Date('Y-m-d H:i:s'))->orWhere('expire', null);
    }

    /**
     * Must add this method after select() method
     */
    public function scopeWithFeedbacksAvg($query)
    {
        return $query->leftJoin('feedbacks', function ($join) {
            $join->on('inds.id', 'feedbacks.feedbackable_id')
                ->where('feedbackable_type', 'ind');
        })
            ->addSelect(DB::raw('inds.id, avg(star) as feedbacks_avg'))
            ->groupBy('id');
    }
}
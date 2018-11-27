<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Models\Thana;
use App\Models\Union;
use \Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Division;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Ind
 *
 * @method static Builder exceptExpired()
 * @method static Builder onlyExpired()
 *
 */
class Ind extends Model
{
    use SoftDeletes;

    protected $dates = ['expire', 'top_expire', 'deleted_at'];


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
        return $this->morphMany(WorkImage::class, 'work_imagable');
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

    public function subCategories()
    {
        return $this->morphToMany(SubCategory::class, 'sub_categoriable');
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
        $query->where('expire', '<', now());
    }

    public function scopeExceptExpired($query)
    {
        $query->where('expire', '>=', now())->orWhere('expire', null);
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
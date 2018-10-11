<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['name', 'is_confirmed'];


    /**********************/
    /***** Relations *****/
    /**********************/

    public function inds()
    {
        return $this->morphedByMany(Ind::class, 'sub_categoriable')->where('is_pending', 0);
    }

    public function orgs()
    {
        return $this->morphedByMany(Org::class, 'sub_categoriable')->where('is_pending', 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function workMethods()
    {
        return $this->belongsToMany(WorkMethod::class, 'ind_work_method')->withPivot('rate');
    }



    /******************/
    /***** Scopes *****/
    /******************/

    public function scopeOnlyInd($query)
    {
        return $query->join('categories', function ($join) {
            $join->on('sub_categories.category_id', 'categories.id')
                ->where('categories.service_type_id', 1);
            })
            ->addSelect(DB::raw('
                sub_categories.id,
                sub_categories.category_id,
                sub_categories.name,
                sub_categories.is_confirmed,
                sub_categories.created_at,
                sub_categories.updated_at,
                categories.service_type_id
            '));
    }

    public function scopeOnlyOrg($query)
    {
        return $query->join('categories', function ($join) {
            $join->on('sub_categories.category_id', 'categories.id')
                ->select('categories.id', 'categories.service_type_id')
                ->where('categories.service_type_id', 2);
            })
            ->addSelect(DB::raw('
                sub_categories.id,
                sub_categories.category_id,
                sub_categories.name,
                sub_categories.is_confirmed,
                sub_categories.created_at,
                sub_categories.updated_at,
                categories.service_type_id
            '));
    }

    public function scopeOnlyConfirmed($query)
    {
        return $query->where('sub_categories.is_confirmed', 1);
    }

    public function scopeOnlyPending($query)
    {
        return $query->where('sub_categories.is_confirmed', 0);
    }
}

<?php

namespace App\Http\ViewComposers;


use App\Models\Category;
use App\Models\Village;
use App\Models\WorkMethod;
use Illuminate\View\View;
use App\Models\SubCategory;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;

class FilterComponentComposer
{
    public function compose(View $view)
    {
        $divisions = Division::select('id', 'bn_name as name')->get();

        if (request()->filled('division')) {

            $districts = District::select('id', 'bn_name as name')
                ->where('division_id', request()->get('division'))
                ->get();

        }
        if (request()->filled('district')) {

            $thanas = Thana::select('id', 'bn_name as name')
                ->where('district_id', request()->get('district'))
                ->where('is_pending', 0)
                ->get();

        }

        if (request()->filled('thana')) {

            $unions = Union::select('id', 'bn_name as name')
                ->where('thana_id', request()->get('thana'))
                ->where('is_pending', 0)
                ->get();

        }

        if (request()->filled('union')) {

            $villages = Village::select('id', 'bn_name as name')
                ->where('union_id', request()->get('union'))
                ->where('is_pending', 0)
                ->get();

        }

        $categories = Category::select('id', 'name', 'service_type_id')
            ->onlyConfirmed()
            ->get();

        if (request()->filled('category')) {

            $subCategories = SubCategory::select('id', 'name')
                ->where('category_id', request()->get('category'))
                ->onlyConfirmed()
                ->get();

        }

        $workMethods = WorkMethod::select('name', 'id')->get();

        $view->with(compact(
            'divisions',
            'districts',
            'thanas',
            'unions',
            'villages',
            'categories',
            'subCategories',
            'workMethods'
        ));
    }
}
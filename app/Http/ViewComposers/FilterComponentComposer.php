<?php

namespace App\Http\ViewComposers;


use App\Models\Category;
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

        $categories = Category::select('id', 'name')
            ->where('is_confirmed', 1)
            ->get();

        if (request()->filled('category')) {

            $subCategories = SubCategory::select('id', 'name')
                ->where('category_id', request()->get('category'))
                ->where('is_confirmed', 1)
                ->get();

        }

        $view->with(compact(
            'divisions',
            'districts',
            'thanas',
            'unions',
            'categories',
            'subCategories'
        ));
    }
}
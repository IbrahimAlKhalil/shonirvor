<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Ind;
use App\Http\Controllers\Controller;
use App\Models\Org;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Sandofvega\Bdgeocode\Models\Division;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::select('id', 'bn_name')->get();
        $categories = Category::select('id', 'name')->where('is_confirmed', 1)->get();

        $ads = Ad::all();
        if ($ads->count() >= 3) {
            $ads = $ads->random(3);
        }

        if ($request->filled('sub-category')) {
            $subCategoryType = SubCategory::find($request->get('sub-category'))->category->type->name;

            if ($subCategoryType === 'ind') {
                $indProviders = Ind::
                    join('sub_categories', 'inds.category_id', 'sub_categories.category_id')
                    ->where('sub_categories.id', $request->get('sub-category'))
                    ->where('sub_categories.is_confirmed', 1);
            }
            elseif ($subCategoryType === 'org') {
                $orgProviders = Org::
                    join('sub_categories', 'inds.category_id', 'sub_categories.category_id')
                    ->where('sub_categories.id', $request->get('sub-category'))
                    ->where('sub_categories.is_confirmed', 1);
            }
        } elseif ($request->filled('category')) {
            $categoryType = Category::find($request->get('category'))->type->name;

            if ($categoryType === 'ind') {
                dd('ind');
                $indProviders = Ind::class;
            }
            elseif ($categoryType === 'org') {
                dd('org');
                $orgProviders = Org::class;
            }
        } else {
            $indProviders = new Ind();
            $orgProviders = new Org();
        }

        function indJoinNfetch($instance)
        {
            return $instance
                ->join('users', 'inds.user_id', 'users.id')
                ->join('categories', 'inds.category_id', 'categories.id')
                ->join('service_types', 'categories.service_type_id', 'service_types.id')
//                ->join('divisions', 'inds.division_id', 'divisions.id')
                ->join('districts', 'inds.district_id', 'districts.id')
                ->join('thanas', 'inds.thana_id', 'thanas.id')
                ->join('unions', 'inds.union_id', 'unions.id')
                ->select([
                    'inds.user_id',
                    'users.name',
                    'users.photo',
                    'inds.mobile',
                    'categories.name as category_name',
                    'service_types.name as type',
                    'inds.district_id',
                    'districts.bn_name as district_name',
                    'inds.thana_id',
                    'thanas.bn_name as thana_name',
                    'inds.union_id',
                    'unions.bn_name as union_name',
                ])
                ->get();
        }

        function orgJoinNfetch($instance)
        {
            return $instance
                ->join('users', 'orgs.user_id', 'users.id')
                ->join('categories', 'orgs.category_id', 'categories.id')
                ->join('service_types', 'categories.service_type_id', 'service_types.id')
//                ->join('divisions', 'orgs.division_id', 'divisions.id')
                ->join('districts', 'orgs.district_id', 'districts.id')
                ->join('thanas', 'orgs.thana_id', 'thanas.id')
                ->join('unions', 'orgs.union_id', 'unions.id')
                ->select([
                    'orgs.user_id',
                    'users.name',
                    'orgs.logo as photo',
                    'orgs.mobile',
                    'categories.name as category_name',
                    'service_types.name as type',
                    'orgs.district_id',
                    'districts.bn_name as district_name',
                    'orgs.thana_id',
                    'thanas.bn_name as thana_name',
                    'orgs.union_id',
                    'unions.bn_name as union_name',
                ])
                ->get();
        }

        if ($request->filled('sub-category') || $request->filled('category'))
        {
            if (isset($indProviders)) {
                $providers = indJoinNfetch($indProviders);
            } elseif ($orgProviders) {
                $providers = orgJoinNfetch($orgProviders);
            }
        } else {
            $indProviders = indJoinNfetch($indProviders);
            $orgProviders = orgJoinNfetch($orgProviders);

            foreach ($orgProviders as $orgProvider) {
                $indProviders->push($orgProvider);
            }
            $providers = $indProviders;
        }

        return view('frontend.filter', compact('divisions', 'categories', 'ads', 'providers'));
    }
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Ind;
use App\Models\Org;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::select('id', 'bn_name')->get();

        if ($request->filled('division')) {

            $districts = District::select('id', 'bn_name')
                ->where('division_id', $request->get('division'))
                ->get();

        }
        if ($request->filled('district')) {

            $thanas = Thana::select('id', 'bn_name')
                ->where('district_id', $request->get('district'))
                ->where('is_pending', 0)
                ->get();

        }

        if ($request->filled('thana')) {

            $unions = Union::select('id', 'bn_name')
                ->where('thana_id', $request->get('thana'))
                ->where('is_pending', 0)
                ->get();

        }

        $categories = Category::select('id', 'name')->where('is_confirmed', 1)->get();

        if ($request->filled('category')) {

            $subCategories = SubCategory::select('id', 'name')
                ->where('category_id', $request->get('category'))
                ->where('is_confirmed', 1)
                ->get();

        }

        $ads = Ad::all();
        if ($ads->count() >= 3) {
            $ads = $ads->random(3);
        }

        if ($request->filled('sub-category')) {

            $subCategoryType = SubCategory::find($request->get('sub-category'))->category->type->name;

            switch ($subCategoryType) {
                case 'ind':
                    $indProviders = Ind::join('sub_categories', 'inds.category_id', 'sub_categories.category_id')
                        ->where('sub_categories.id', $request->get('sub-category'))
                        ->where('sub_categories.is_confirmed', 1);

                    if ($request->filled('union')) {

                        $indProviders->where('inds.union_id', $request->get('union'));

                    } elseif ($request->filled('thana')) {

                        $indProviders->where('inds.thana_id', $request->get('thana'));

                    } elseif ($request->filled('district')) {

                        $indProviders->where('inds.district_id', $request->get('district'));

                    } elseif ($request->filled('division')) {

                        $indProviders->where('inds.division_id', $request->get('division'));

                    }
                    break;

                case 'org':
                    $orgProviders = Org::join('sub_categories', 'inds.category_id', 'sub_categories.category_id')
                        ->where('sub_categories.id', $request->get('sub-category'))
                        ->where('sub_categories.is_confirmed', 1);

                    if ($request->filled('union')) {

                        $orgProviders->where('orgs.union_id', $request->get('union'));

                    } elseif ($request->filled('thana')) {

                        $orgProviders->where('orgs.thana_id', $request->get('thana'));

                    } elseif ($request->filled('district')) {

                        $orgProviders->where('orgs.district_id', $request->get('district'));

                    } elseif ($request->filled('division')) {

                        $orgProviders->where('orgs.division_id', $request->get('division'));

                    }
                    break;
            }

        } elseif ($request->filled('category')) {

            $categoryType = Category::find($request->get('category'))->type->name;

            switch ($categoryType) {
                case 'ind':
                    $indProviders = Ind::where('categories.id', $request->get('category'));

                    if ($request->filled('union')) {

                        $indProviders->where('inds.union_id', $request->get('union'));

                    } elseif ($request->filled('thana')) {

                        $indProviders->where('inds.thana_id', $request->get('thana'));

                    } elseif ($request->filled('district')) {

                        $indProviders->where('inds.district_id', $request->get('district'));

                    } elseif ($request->filled('division')) {

                        $indProviders->where('inds.division_id', $request->get('division'));

                    }
                    break;

                case 'org':
                    $orgProviders = Org::where('categories.id', $request->get('category'));

                    if ($request->filled('union')) {

                        $orgProviders->where('orgs.union_id', $request->get('union'));

                    } elseif ($request->filled('thana')) {

                        $orgProviders->where('orgs.thana_id', $request->get('thana'));

                    } elseif ($request->filled('district')) {

                        $orgProviders->where('orgs.district_id', $request->get('district'));

                    } elseif ($request->filled('division')) {

                        $orgProviders->where('orgs.division_id', $request->get('division'));

                    }
                    break;
            }

        } elseif ($request->filled('union')) {

            $indProviders = Ind::where('inds.union_id', $request->get('union'));
            $orgProviders = Org::where('orgs.union_id', $request->get('union'));

        } elseif ($request->filled('thana')) {

            $indProviders = Ind::where('inds.thana_id', $request->get('thana'));
            $orgProviders = Org::where('orgs.thana_id', $request->get('thana'));

        } elseif ($request->filled('district')) {

            $indProviders = Ind::where('inds.district_id', $request->get('district'));
            $orgProviders = Org::where('orgs.district_id', $request->get('district'));

        } elseif ($request->filled('division')) {

            $indProviders = Ind::where('inds.division_id', $request->get('division'));
            $orgProviders = Org::where('orgs.division_id', $request->get('division'));

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
                ->join('districts', 'inds.district_id', 'districts.id')
                ->join('thanas', 'inds.thana_id', 'thanas.id')
                ->join('unions', 'inds.union_id', 'unions.id')
                ->select([
                    'inds.id',
                    'inds.user_id',
                    'users.name',
                    'users.photo',
                    'inds.mobile',
                    'categories.name as category_name',
                    'service_types.name as type',
                    'districts.bn_name as district_name',
                    'thanas.bn_name as thana_name',
                    'unions.bn_name as union_name'
                ])
                ->where('categories.is_confirmed', 1)
                ->get();
        }

        function orgJoinNfetch($instance)
        {
            return $instance
                ->join('users', 'orgs.user_id', 'users.id')
                ->join('categories', 'orgs.category_id', 'categories.id')
                ->join('service_types', 'categories.service_type_id', 'service_types.id')
                ->join('districts', 'orgs.district_id', 'districts.id')
                ->join('thanas', 'orgs.thana_id', 'thanas.id')
                ->join('unions', 'orgs.union_id', 'unions.id')
                ->select([
                    'orgs.id',
                    'orgs.user_id',
                    'orgs.name',
                    'orgs.logo as photo',
                    'orgs.mobile',
                    'categories.name as category_name',
                    'service_types.name as type',
                    'districts.bn_name as district_name',
                    'thanas.bn_name as thana_name',
                    'unions.bn_name as union_name'
                ])
                ->where('categories.is_confirmed', 1)
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

//            dd($indProviders);

            foreach ($orgProviders as $orgProvider) {

                $indProviders->push($orgProvider);

            }
            $providers = $indProviders;

        }

        return view('frontend.filter', compact(
            'divisions',
            'districts',
            'thanas',
            'unions',
            'categories',
            'subCategories',
            'ads',
            'providers'
        ));
    }
}
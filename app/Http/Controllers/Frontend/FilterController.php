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
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class FilterController extends Controller
{
    private $showPerPage = 14;

    public function index(Request $request)
    {
        if ($request->filled('sub-category')) {

            $subCategoryType = SubCategory::find($request->get('sub-category'))->category->type->name;

            switch ($subCategoryType) {
                case 'ind':
                    $indProviders = Ind::join('sub_categoriables', 'inds.id', 'sub_categoriables.sub_categoriable_id')
                        ->join('sub_categories', 'sub_categoriables.sub_category_id', 'sub_categories.id')
                        ->where('sub_categoriables.sub_category_id', $request->get('sub-category'))
                        ->where('sub_categoriables.sub_categoriable_type', 'ind')
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
                    $orgProviders = Org::join('sub_categoriables', 'orgs.id', 'sub_categoriables.sub_categoriable_id')
                        ->join('sub_categories', 'sub_categoriables.sub_category_id', 'sub_categories.id')
                        ->where('sub_categoriables.sub_category_id', $request->get('sub-category'))
                        ->where('sub_categoriables.sub_categoriable_type', 'org')
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
                ->with('feedbacks')
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
                ->where('inds.is_pending', 0)
                ->where('categories.is_confirmed', 1)
                ->where('thanas.is_pending', 0)
                ->where('unions.is_pending', 0)
                ->get();
        }

        function orgJoinNfetch($instance)
        {
            return $instance
                ->with('feedbacks')
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
                ->where('orgs.is_pending', 0)
                ->where('categories.is_confirmed', 1)
                ->where('thanas.is_pending', 0)
                ->where('unions.is_pending', 0)
                ->get();
        }

        if ($request->filled('sub-category') || $request->filled('category'))
        {

            if (isset($indProviders)) {

                $services = indJoinNfetch($indProviders)->sortByDesc('feedbacks');

            } elseif ($orgProviders) {

                $services = orgJoinNfetch($orgProviders)->sortByDesc('feedbacks');

            }

        } else {

            $indProviders = indJoinNfetch($indProviders);
            $orgProviders = orgJoinNfetch($orgProviders);

            foreach ($orgProviders as $orgProvider) {

                $indProviders->push($orgProvider);

            }
            $services = $indProviders->sortByDesc('feedbacks');

        }

        $perPagedData = $services->slice(($request->get('page')-1) * $this->showPerPage, $this->showPerPage)->all();
        $providers = new Paginator($perPagedData, count($services), $this->showPerPage, $request->get('page'), [
            'path' => route('frontend.filter')
        ]);

        return view('frontend.filter', compact('providers'));
    }
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Ind;
use App\Models\Org;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class FilterController extends Controller
{
    private $showPerPage = 14;
    private $page = 1;
    private $paginatorPath = '';
    private $divisionId = false;
    private $districtId = false;
    private $thanaId = false;
    private $unionId = false;
    private $type = false;
    private $categoryId = false;
    private $subCategoryId = false;
    private $price = false;

    public function __construct(Request $request)
    {
        $this->paginatorPath = route('frontend.filter').'?';

        if ($request->filled('page')) {
            $this->page = $request->get('page');
        }

        if ($request->filled('division')) {
            $this->divisionId = $request->get('division');
            $this->paginatorPath .= '&division='.$this->divisionId;
        }

        if ($request->filled('district')) {
            $this->districtId = $request->get('district');
            $this->paginatorPath .= '&district='.$this->districtId;
        }

        if ($request->filled('thana')) {
            $this->thanaId = $request->get('thana');
            $this->paginatorPath .= '&thana='.$this->thanaId;
        }

        if ($request->filled('union')) {
            $this->unionId = $request->get('union');
            $this->paginatorPath .= '&union='.$this->unionId;
        }

        if ($request->filled('type')) {
            $this->type = $request->get('type');
            $this->paginatorPath .= '&type='.$this->type;
        }

        if ($request->filled('category')) {
            $this->categoryId = $request->get('category');
            $this->paginatorPath .= '&category='.$this->categoryId;
        }

        if ($request->filled('sub-category')) {
            $this->subCategoryId = $request->get('sub-category');
            $this->paginatorPath .= '&sub-category='.$this->subCategoryId;
        }

        if ($request->filled('price')) {
            $this->price = $request->get('price');
            $this->paginatorPath .= '&price='.$this->price;
        }
    }


    public function __invoke()
    {

        /***** Query Builder *****/
        /*************************/

        if ($this->subCategoryId) {

            $subCategoryType = SubCategory::findOrFail($this->subCategoryId)->category->type->name;

            switch ($subCategoryType) {
                case 'ind':
                    $indProviders = Ind::join('sub_categoriables', 'inds.id', 'sub_categoriables.sub_categoriable_id')
                        ->join('sub_categories', 'sub_categoriables.sub_category_id', 'sub_categories.id')
                        ->where('sub_categoriables.sub_category_id', $this->subCategoryId)
                        ->where('sub_categoriables.sub_categoriable_type', 'ind')
                        ->where('sub_categories.is_confirmed', 1);

                    if ($this->unionId) {

                        $indProviders->where('inds.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $indProviders->where('inds.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $indProviders->where('inds.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $indProviders->where('inds.division_id', $this->divisionId);

                    }
                    break;

                case 'org':
                    $orgProviders = Org::join('sub_categoriables', 'orgs.id', 'sub_categoriables.sub_categoriable_id')
                        ->join('sub_categories', 'sub_categoriables.sub_category_id', 'sub_categories.id')
                        ->where('sub_categoriables.sub_category_id', $this->subCategoryId)
                        ->where('sub_categoriables.sub_categoriable_type', 'org')
                        ->where('sub_categories.is_confirmed', 1);

                    if ($this->unionId) {

                        $orgProviders->where('orgs.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $orgProviders->where('orgs.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $orgProviders->where('orgs.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $orgProviders->where('orgs.division_id', $this->divisionId);

                    } elseif ($this->price) {

                        $orgProviders->join('org_sub_category_rates', 'orgs.id', 'org_sub_category_rates.org_id')
                            ->addSelect('org_sub_category_rates.rate');

                    }
                    break;
            }

        } elseif ($this->categoryId) {

            $categoryType = Category::findOrFail($this->categoryId)->type->name;

            switch ($categoryType) {
                case 'ind':
                    $indProviders = Ind::where('categories.id', $this->categoryId);

                    if ($this->unionId) {

                        $indProviders->where('inds.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $indProviders->where('inds.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $indProviders->where('inds.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $indProviders->where('inds.division_id', $this->divisionId);

                    }
                    break;

                case 'org':
                    $orgProviders = Org::where('categories.id', $this->categoryId);

                    if ($this->unionId) {

                        $orgProviders->where('orgs.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $orgProviders->where('orgs.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $orgProviders->where('orgs.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $orgProviders->where('orgs.division_id', $this->divisionId);

                    }
                    break;
            }

        } elseif ($this->unionId) {

            if ($this->type && $this->type == 'ind') {

                $indProviders = Ind::where('inds.union_id', $this->unionId);

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = Org::where('orgs.union_id', $this->unionId);

            } else {

                $indProviders = Ind::where('inds.union_id', $this->unionId);
                $orgProviders = Org::where('orgs.union_id', $this->unionId);

            }

        } elseif ($this->thanaId) {

            if ($this->type && $this->type == 'ind') {

                $indProviders = Ind::where('inds.thana_id', $this->thanaId);

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = Org::where('orgs.thana_id', $this->thanaId);

            } else {

                $indProviders = Ind::where('inds.thana_id', $this->thanaId);
                $orgProviders = Org::where('orgs.thana_id', $this->thanaId);

            }

        } elseif ($this->districtId) {

            if ($this->type && $this->type == 'ind') {

                $indProviders = Ind::where('inds.district_id', $this->districtId);

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = Org::where('orgs.district_id', $this->districtId);

            } else {

                $indProviders = Ind::where('inds.district_id', $this->districtId);
                $orgProviders = Org::where('orgs.district_id', $this->districtId);

            }

        } elseif ($this->divisionId) {

            if ($this->type && $this->type == 'ind') {

                $indProviders = Ind::where('inds.division_id', $this->divisionId);

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = Org::where('orgs.division_id', $this->divisionId);

            } else {

                $indProviders = Ind::where('inds.division_id', $this->divisionId);
                $orgProviders = Org::where('orgs.division_id', $this->divisionId);

            }

        } else {

            if ($this->type && $this->type == 'ind') {

                $indProviders = new Ind();

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = new Org();

            } else {

                $indProviders = new Ind();
                $orgProviders = new Org();

            }

        }


        /***** Helper Functions for Fetch *****/
        /**************************************/

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
                ->withFeedbacksAvg()
                ->where([
                    ['inds.is_pending', 0],
                    ['thanas.is_pending', 0],
                    ['unions.is_pending', 0],
                    ['categories.is_confirmed', 1]
                ])
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
                ->addSelect([
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
                ->withFeedbacksAvg()
                ->where([
                    ['orgs.is_pending', 0],
                    ['thanas.is_pending', 0],
                    ['unions.is_pending', 0],
                    ['categories.is_confirmed', 1]
                ])
                ->get();
        }


        /***** Fetch & Marge *****/
        /*************************/

        if ($this->subCategoryId || $this->categoryId || $this->type)
        {

            if (isset($indProviders)) {

                $services = indJoinNfetch($indProviders);

            } elseif ($orgProviders) {

                $services = orgJoinNfetch($orgProviders);

            }

            if ($this->price && $this->price == 'high') {

                $services = $services->sortByDesc('rate');

            } elseif ($this->price && $this->price == 'low') {

                $services = $services->sortBy('rate');

            } else {

                $services = $services->sortByDesc('feedbacks_avg');

            }

        } else {

            $indProviders = indJoinNfetch($indProviders);
            $orgProviders = orgJoinNfetch($orgProviders);

            foreach ($orgProviders as $orgProvider) {

                $indProviders->push($orgProvider);

            }

            $services = $indProviders->sortByDesc('feedbacks_avg');

        }


        /***** Making Paginator *****/
        /****************************/

        $perPagedData = $services
            ->slice(($this->page - 1) * $this->showPerPage, $this->showPerPage)
            ->all();

        $providers = new Paginator($perPagedData, count($services), $this->showPerPage, $this->page, [
            'path' => $this->paginatorPath
        ]);


        /***** Return *****/
        /******************/

        return view('frontend.filter', compact('providers'));
    }
}
<?php

namespace App\Http\Controllers\Backend;

use App\Events\NotificationSent;
use App\Events\SmsSent;
use App\Jobs\SendNotification;
use App\Jobs\SendSms;
use App\Models\Division;
use App\Models\Ind;
use App\Models\MessageTemplate;
use App\Models\Org;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    private $showPerPage = 10;
    private $page = 1;
    private $paginatorPath = '';

    private $type = false;

    private $divisionId = false;
    private $districtId = false;
    private $thanaId = false;
    private $unionId = false;
    private $villageId = false;

    private $categoryId = false;
    private $subCategoryId = false;

    private $methodId = false;
    private $price = false;

    public function __construct(Request $request)
    {
        $this->paginatorPath = route('frontend.filter') . '?';

        if ($request->filled('page')) {
            $this->page = $request->post('page');
        }

        if ($request->filled('type')) {
            $this->type = $request->post('type');
            $this->paginatorPath .= '&type=' . $this->type;
        }

        if ($request->filled('division')) {
            $this->divisionId = $request->post('division');
            $this->paginatorPath .= '&division=' . $this->divisionId;
        }

        if ($request->filled('district')) {
            $this->districtId = $request->post('district');
            $this->paginatorPath .= '&district=' . $this->districtId;
        }

        if ($request->filled('thana')) {
            $this->thanaId = $request->post('thana');
            $this->paginatorPath .= '&thana=' . $this->thanaId;
        }

        if ($request->filled('union')) {
            $this->unionId = $request->post('union');
            $this->paginatorPath .= '&union=' . $this->unionId;
        }

        if ($request->filled('village')) {
            $this->villageId = $request->post('village');
            $this->paginatorPath .= '&village=' . $this->villageId;
        }

        if ($request->filled('category')) {
            $this->categoryId = $request->post('category');
            $this->paginatorPath .= '&category=' . $this->categoryId;
        }

        if ($request->filled('sub-category')) {
            $this->subCategoryId = $request->post('sub-category');
            $this->paginatorPath .= '&sub-category=' . $this->subCategoryId;
        }

        if ($request->filled('method')) {
            $this->methodId = $request->post('method');
            $this->paginatorPath .= '&price=' . $this->methodId;
        }

        if ($request->filled('price')) {
            $this->price = $request->post('price');
            $this->paginatorPath .= '&price=' . $this->price;
        }

        if ($request->filled('per-page') && (int)$request->post('per-page')) {
            $this->showPerPage = $request->post('per-page');
        }
    }

    public function index()
    {
        $divisions = Division::all();

        $indPackages = DB::select($this->packageNameQuery(1));
        $orgPackages = DB::select($this->packageNameQuery(2));
        $smsTemplates = MessageTemplate::select('id', 'name', 'message')->get();

        return view('backend.service-providers', compact('divisions', 'indPackages', 'orgPackages', 'smsTemplates'));
    }

    private function packageNameQuery(int $typeId)
    {
        return "
        select `values`.value as name, packages.id
        from packages
              join package_types on packages.package_type_id = package_types.id
              join package_values `values` on packages.id = `values`.package_id
              join package_properties properties on `values`.package_property_id = properties.id
        where package_type_id = $typeId
          and properties.name = 'name';
        ";
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'message' => 'required|string'
        ]);

        $ids = explode(',', $request->post('ids'));

        if (!User::whereIn('id', $ids)->exists()) {
            return abort(422);
        }

        return 'Hella';

        $message = $request->post('message');
        $this->dispatch(new SendSms(array_unique($ids), $message));

        return '...';
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'services.*.id' => 'required',
            'services.*.type' => 'required',
            'message' => 'required|string'
        ]);

        $message = $request->post('message');

        $indIds = $this->getIds($request->post('services'), 'ind');
        $orgIds = $this->getIds($request->post('services'), 'org');

        if (!(Ind::withTrashed()->whereIn('id', $indIds)->exists()) || !(Org::withTrashed()->whereIn('id', $orgIds)->exists())) {
            return abort(422);
        }

        $this->dispatch(new SendNotification($indIds, $orgIds, $message));

        return '...';
    }

    public function getIds($services, $type)
    {
        $ids = [];
        foreach (array_filter($services, function ($service) use ($type) {
            return $service['type'] == $type;
        }) as $service) {
            array_push($ids, $service['id']);
        }

        return $ids;
    }


    public function getData()
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

                    if ($this->villageId) {

                        $indProviders->where('inds.village_id', $this->villageId);

                    } elseif ($this->unionId) {

                        $indProviders->where('inds.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $indProviders->where('inds.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $indProviders->where('inds.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $indProviders->where('inds.division_id', $this->divisionId);

                    }

                    if ($this->methodId) {

                        $indProviders->join('ind_work_method', function ($join) {
                            $join->on('inds.id', 'ind_work_method.ind_id')
                                ->where('ind_work_method.work_method_id', $this->methodId)
                                ->where('ind_work_method.sub_category_id', $this->subCategoryId);
                        });

                        if ($this->price) {

                            $indProviders->addSelect('ind_work_method.rate');

                        }

                    }

                    break;

                case 'org':
                    $orgProviders = Org::join('sub_categoriables', 'orgs.id', 'sub_categoriables.sub_categoriable_id')
                        ->join('sub_categories', 'sub_categoriables.sub_category_id', 'sub_categories.id')
                        ->where('sub_categoriables.sub_category_id', $this->subCategoryId)
                        ->where('sub_categoriables.sub_categoriable_type', 'org')
                        ->where('sub_categories.is_confirmed', 1);

                    if ($this->villageId) {

                        $orgProviders->where('orgs.village_id', $this->villageId);

                    } elseif ($this->unionId) {

                        $orgProviders->where('orgs.union_id', $this->unionId);

                    } elseif ($this->thanaId) {

                        $orgProviders->where('orgs.thana_id', $this->thanaId);

                    } elseif ($this->districtId) {

                        $orgProviders->where('orgs.district_id', $this->districtId);

                    } elseif ($this->divisionId) {

                        $orgProviders->where('orgs.division_id', $this->divisionId);

                    }

                    if ($this->price) {

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

                    if ($this->villageId) {

                        $indProviders->where('inds.village_id', $this->villageId);

                    } elseif ($this->unionId) {

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

                    if ($this->villageId) {

                        $orgProviders->where('orgs.village_id', $this->villageId);

                    } elseif ($this->unionId) {

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

        } elseif ($this->villageId) {

            if ($this->type && $this->type == 'ind') {

                $indProviders = Ind::where('inds.village_id', $this->villageId);

            } elseif ($this->type && $this->type == 'org') {

                $orgProviders = Org::where('orgs.village_id', $this->villageId);

            } else {

                $indProviders = Ind::where('inds.village_id', $this->villageId);
                $orgProviders = Org::where('orgs.village_id', $this->villageId);

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
                ->withTrashed()
                ->join('users', 'inds.user_id', 'users.id')
                ->join('categories', 'inds.category_id', 'categories.id')
                ->join('service_types', 'categories.service_type_id', 'service_types.id')
                ->join('districts', 'inds.district_id', 'districts.id')
                ->join('thanas', 'inds.thana_id', 'thanas.id')
                ->join('unions', 'inds.union_id', 'unions.id')
                ->with([
                    'payments' => function ($query) {
                        $query->join('packages', function ($join) {
                            $join->on('packages.id', 'incomes.package_id')->where('packages.package_type_id', 2);
                        })
                            ->with('package.properties')
                            ->latest('incomes.updated_at')
                            ->first();
                    }
                ])
                ->addSelect([
                    'inds.id',
                    'inds.user_id',
                    'inds.expire',
                    'inds.top_expire',
                    'inds.deleted_at',
                    'users.name',
                    'users.photo',
                    'inds.mobile',
                    'categories.name as category_name',
                    'service_types.name as type'
                ])
                ->where([
                    ['categories.is_confirmed', 1]
                ])->get();
        }

        function orgJoinNfetch($instance)
        {
            return $instance
                ->withTrashed()
                ->join('users', 'orgs.user_id', 'users.id')
                ->join('categories', 'orgs.category_id', 'categories.id')
                ->join('service_types', 'categories.service_type_id', 'service_types.id')
                ->join('districts', 'orgs.district_id', 'districts.id')
                ->join('thanas', 'orgs.thana_id', 'thanas.id')
                ->join('unions', 'orgs.union_id', 'unions.id')
                ->with([
                    'payments' => function ($query) {
                        $query
                            ->join('packages', function ($join) {
                                $join->on('packages.id', 'incomes.package_id')->where('packages.package_type_id', 2);
                            })
                            ->with('package.properties')
                            ->latest('incomes.updated_at')
                            ->first();
                    }
                ])
                ->addSelect([
                    'orgs.id',
                    'orgs.user_id',
                    'orgs.name',
                    'orgs.expire',
                    'orgs.top_expire',
                    'orgs.deleted_at',
                    'orgs.logo as photo',
                    'orgs.mobile',
                    'categories.name as category_name',
                    'service_types.name as type'
                ])
                ->where([
                    ['categories.is_confirmed', 1]
                ])->get();
        }


        /***** Fetch & Marge *****/
        /*************************/

        if ($this->subCategoryId || $this->categoryId || $this->type) {

            if (isset($indProviders)) {

                $services = indJoinNfetch($indProviders);

            } else {

                $services = orgJoinNfetch($orgProviders);

            }

        } else {

            $indProviders = indJoinNfetch($indProviders);
            $orgProviders = orgJoinNfetch($orgProviders);

            foreach ($orgProviders as $orgProvider) {

                $indProviders->push($orgProvider);

            }

            $services = $indProviders->sortByDesc('created_at');

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

        return $providers;
    }
}

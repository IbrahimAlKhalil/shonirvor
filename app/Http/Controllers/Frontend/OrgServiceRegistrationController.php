<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Models\Category;
use App\Models\ServiceType;
use App\Models\SubCategory;
use App\Http\Requests\StoreOrg;
use App\Http\Requests\UpdateOrg;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Illuminate\Support\Facades\Storage;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\District;

class OrgServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $orgs = $user->orgs('pending')->get();

        $categories = Category::getAll('org')->get();
        $divisions = Division::all();
        $classesToAdd = ['active', 'disabled'];
        $compact = compact('classesToAdd', 'orgs', 'divisions', 'categories', 'user');
        $view = 'frontend.registration.org-service.confirm';
        $count = $orgs->count();

        // check what if current user didn't reach at the maximum pending request
        if ($count >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view($view, $compact);
        }

        // check what if current user has less than 3 pending request
        if ($count < 3 && $count >= 1) {
            $compact['classesToAdd'] = ['active', ''];
            // didn't reach at the maximum
            // redirect them to the confirmation page
            return view($view, $compact);
        }

        // inds, classesToAdd are unnecessary for index
        unset($compact['orgs'], $compact['classesToAdd']);

        // user didn't make any request for being organizational service provider
        return view('frontend.registration.org-service.index', $compact);
    }

    public function store(StoreOrg $request)
    {
        DB::beginTransaction();

        $user = Auth::user();
        $orgs = $user->orgs('pending')->get();

        // check what if current user didn't reach at the maximum pending request
        if ($orgs->count() >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            $categories = Category::getAll('org')->get();
            $divisions = Division::all();
            $classesToAdd = ['active', 'disabled'];
            $compact = compact('classesToAdd', 'orgs', 'divisions', 'categories', 'user');
            return view('frontend.registration.org-service.confirm', $compact);
        }

        // handle category  and sub-category request
        // TODO:: Do some custom validation for category and subcategory

        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';
        $category = Category::find($request->post('category'));
        $subCategories = !$isCategoryRequest ? SubCategory::whereIn('id', array_map(function ($item) {
            return $item['id'];
        }, $request->post('sub-categories')))->get() : null;

        // Create categories
        if ($isCategoryRequest) {
            $category = new Category;
            $category->service_type_id = 2;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();
        }

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $isVillageRequest = $request->has('no-village') && $request->post('no-village') == 'on';
        $thana = $request->post('thana');
        $union = $request->post('union');
        $village = $request->post('village');

        if ($isThanaRequest) {
            $newThana = new Thana;
            $newThana->district_id = $request->post('district');
            $newThana->bn_name = $request->post('thana-request');
            $newThana->is_pending = 1;
            $newThana->save();
            $thana = $newThana->id;
        }

        if ($isUnionRequest) {
            $newUnion = new Union;
            $newUnion->thana_id = $thana;
            $newUnion->bn_name = $request->post('union-request');
            $newUnion->is_pending = 1;
            $newUnion->save();
            $union = $newUnion->id;
        }

        if ($isVillageRequest) {
            $newVillage = new Village;
            $newVillage->union_id = $union;
            $newVillage->bn_name = $request->post('village-request');
            $newVillage->is_pending = 1;
            $newVillage->save();
            $village = $newVillage->id;
        }

        $org = new Org;
        $org->user_id = $user->id;
        $org->division_id = $request->post('division');
        $org->district_id = $request->post('district');
        $org->thana_id = $thana;
        $org->union_id = $union;
        $org->village_id = $village;

        $org->name = $request->post('name');
        $org->description = $request->post('description');
        $org->mobile = $request->post('mobile');
        $org->referrer = $request->post('referrer');
        $org->email = $request->post('email');
        $org->category_id = $category->id;
        $org->website = $request->post('website');
        $org->facebook = $request->post('facebook');
        $org->address = $request->post('address');
        $org->save();
        if ($request->hasFile('trade-license')) {
            $org->trade_license = $request->file('trade-license')->store('org/' . $org->id . '/' . 'docs');
        }
        if ($request->hasFile('logo')) {
            $org->logo = $request->file('logo')->store('org/' . $org->id);
        }
        $org->save();

        // associate sub-categories$org
        !$isCategoryRequest && $org->subCategories()->saveMany($subCategories);

        $data = [];
        foreach ($request->post('sub-categories') as $subCategory) {
            if (isset($subCategory['id']) && !is_null($subCategory['id'])) {
                array_push($data, [
                    'org_id' => $org->id,
                    'sub_category_id' => $subCategory['id'],
                    'rate' => $subCategory['rate']
                ]);
            }
        }

        // Create sub categories
        if ($isSubCategoryRequest) {
            foreach ($request->post('sub-category-requests') as $subCategory) {
                if (isset($subCategory['name'])) {
                    $newSubCategory = new SubCategory;
                    $newSubCategory->category_id = $category->id;
                    $newSubCategory->name = $subCategory['name'];
                    $newSubCategory->is_confirmed = 0;
                    $newSubCategory->save();

                    $org->subCategories()->attach($newSubCategory);

                    array_push($data, [
                        'org_id' => $org->id,
                        'sub_category_id' => $newSubCategory->id,
                        'rate' => $subCategory['rate']
                    ]);
                }
            }
        }

        DB::table('org_sub_category_rates')->insert($data);


        // User
        $user->nid = $request->post('nid');
        $user->save();

        // work images
        if ($request->has('images') && $request->hasFile('images')) {
            $images = [];
            // TODO:: Validation
            foreach ($request->post('images') as $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && array_push($images, ['description' => $image['description']]);
            }
            foreach ($request->file('images') as $key => $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && $images[$key]['path'] = $image['file']->store('ind/' . $org->id . '/' . 'images');
            }

            $org->workImages()->createMany($images);
        }

        // identities
        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $identity) {
                array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
            }
        }

        DB::commit();

        return back()->with('success', 'ধন্যবাদ! আমরা আপনার অনুরোধ যত তাড়াতাড়ি সম্ভব পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
    }


    public function update(UpdateOrg $request, $id)
    {
        $user = Auth::user();
        $org = Org::find($id);

        // TODO:: Move this validation to a requests class
        if ($org->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }

        DB::beginTransaction();

        // handle category  and sub-category request
        // TODO:: Do some custom validation for category and subcategory

        $previousCategory = $org->category;
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';
        $category = Category::find($request->post('category'));
        $subCategories = !$isCategoryRequest ? SubCategory::all()->whereIn('id', $request->post('sub-categories')) : null;

        // Create categories
        if ($isCategoryRequest && $previousCategory->is_confirmed == 0) {
            $category = $org->category;
            $org->category()->update(['name' => $request->post('category-request')]);
        } else if ($isCategoryRequest && $previousCategory->is_confirmed == 1) {
            $category = new Category;
            $category->service_type_id = 2;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();
        }

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $previousThana = $org->thana;
        $previousUnion = $org->union;
        $previousVillage = $org->village;
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $isVillageRequest = $request->has('no-village') && $request->post('no-village') == 'on';
        $thana = $request->post('thana');
        $union = $request->post('union');
        $village = $request->post('village');

        if ($isThanaRequest) {
            $newThana = new Thana;
            $newThana->district_id = $request->post('district');
            $newThana->bn_name = $request->post('thana-request');
            $newThana->is_pending = 1;
            $newThana->save();
            $thana = $newThana->id;
        }

        if ($isUnionRequest) {
            $newUnion = new Union;
            $newUnion->thana_id = $thana;
            $newUnion->bn_name = $request->post('union-request');
            $newUnion->is_pending = 1;
            $newUnion->save();
            $union = $newUnion->id;
        }

        if ($isVillageRequest) {
            $newVillage = new Village;
            $newVillage->union_id = $union;
            $newVillage->bn_name = $request->post('village-request');
            $newVillage->is_pending = 1;
            $newVillage->save();
            $village = $newVillage->id;
        }

        $org->division_id = $request->post('division');
        $org->district_id = $request->post('district');
        $org->thana_id = $thana;
        $org->union_id = $union;
        $org->village_id = $village;
        $org->name = $request->post('name');
        $org->description = $request->post('description');
        $org->mobile = $request->post('mobile');
        $org->email = $request->post('email');
        $org->category_id = $category->id;
        $org->website = $request->post('website');
        $org->facebook = $request->post('facebook');
        $org->address = $request->post('address');

        if ($request->hasFile('trade-license')) {
            // delete old file
            Storage::delete($org->trade_license);
            $org->trade_license = $request->file('trade-license')->store('org/' . $org->id . '/' . 'docs');
        }
        if ($request->hasFile('logo')) {
            // delete old file
            Storage::delete($org->logo);
            $org->trade_license = $request->file('logo')->store('org/' . $org->id);
        }
        $org->save();

        // delete category and subcategories
        DB::table('org_sub_category_rates')->where('org_id', $org->id)->delete();
        $previousRequested = $org->subCategories('requested');
        $org->subCategories()->detach();
        $previousRequested->delete();

        if (!$isVillageRequest && $previousVillage->is_pending == 1) {
            $previousVillage->delete();
        }

        if (!$isCategoryRequest && $previousCategory->is_confirmed = 0) {
            $previousCategory->delete();
        }

        if (!$isUnionRequest && $previousUnion->is_pending == 1) {
            $previousUnion->delete();
        }

        if (!$isThanaRequest && $previousThana->is_pending == 1) {
            $previousThana->delete();
        }

        // associate sub-categories
        !$isCategoryRequest && $org->subCategories()->saveMany($subCategories);

        $data = [];
        foreach ($request->post('sub-categories') as $subCategory) {
            if (isset($subCategory['id']) && !is_null($subCategory['id'])) {
                array_push($data, [
                    'org_id' => $org->id,
                    'sub_category_id' => $subCategory['id'],
                    'rate' => $subCategory['rate']
                ]);
            }
        }

        // Create sub categories
        if ($isSubCategoryRequest) {
            foreach ($request->post('sub-category-requests') as $subCategory) {
                if (isset($subCategory['name'])) {
                    $newSubCategory = new SubCategory;
                    $newSubCategory->category_id = $category->id;
                    $newSubCategory->name = $subCategory['name'];
                    $newSubCategory->save();

                    $org->subCategories()->attach($newSubCategory);

                    array_push($data, [
                        'org_id' => $org->id,
                        'sub_category_id' => $newSubCategory->id,
                        'rate' => $subCategory['rate']
                    ]);
                }
            }
        }

        DB::table('org_sub_category_rates')->insert($data);

        // User
        $user->nid = $request->post('nid');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();

        // work images
        if ($request->has('images') && $request->hasFile('images')) {
            $images = [];
            // TODO:: Validation
            foreach ($request->post('images') as $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && array_push($images, ['description' => $image['description']]);
            }
            foreach ($request->file('images') as $key => $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && $images[$key]['path'] = $image['file']->store('ind/' . $org->id . '/' . 'images');
            }

            $org->workImages()->createMany($images);
        }

        // identities
        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $identity) {
                array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
            }
        }

        DB::commit();

        return back()->with('success', 'সম্পন্ন!');
    }

    public function edit($id)
    {
        $org = Org::with(['division', 'district', 'thana', 'union', 'subCategoryRates'])->find($id);

        if ($org->user_id != Auth::id() && $org->is_pending == 0) {
            return redirect(route('organization-service-registration.index'));
        }

        $allOrg = $org->user->inds();
        $pendingOrgs = $org->user->inds();

        $canEditNid = false;
        if (!$allOrg->count() || $pendingOrgs->onlyPending()->count() == $allOrg->count()) {
            $canEditNid = true;
        }

        $categories = Category::onlyOrg()->onlyConfirmed()->get();
        $subCategories = SubCategory::where('is_confirmed', 1)->whereCategoryId($org->category_id)->get();
        $orgSubCategories = $org->subCategoryRates;
        $isNoSubCategory = $orgSubCategories->filter(function ($sub) {
            return $sub['is_confirmed'] == 0;
        })->count();

        $divisions = Division::all();
        $districts = District::whereDivisionId($org->division_id)->get();
        $thanas = $org->district->thanas()->whereIsPending(0)->get();
        $unions = $org->thana->unions()->whereIsPending(0)->get();
        $villages = Village::whereUnionId($org->union->id)->whereIsPending(0)->get();

        return view('frontend.registration.org-service.edit', compact('org', 'workMethods', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions', 'villages', 'orgSubCategories', 'allOrg', 'isNoSubCategory'));
    }
}

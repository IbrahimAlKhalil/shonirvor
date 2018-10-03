<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Models\Category;
use App\Models\ServiceType;
use App\Models\SubCategory;
use App\Http\Requests\StoreOrg;
use App\Http\Requests\UpdateOrg;
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
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'orgs', 'divisions', 'isPicExists', 'categories');
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
            $isPicExists = $user->photo;
            $compact = compact('classesToAdd', 'orgs', 'divisions', 'isPicExists', 'categories');
            return view('frontend.registration.org-service.confirm', $compact);
        }

        // handle category  and sub-category request
        // TODO:: Do some custom validation for category and subcategory

        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';
        $category = Category::find($request->post('category'));
        $subCategories = !$isCategoryRequest ? SubCategory::all()->whereIn('id', $request->post('sub-categories')) : null;
        $requestedSubCategories = [];

        // Create categories
        if ($isCategoryRequest) {
            $serviceTypeId = ServiceType::getThe('org')->id;
            $category = new Category;
            $category->service_type_id = $serviceTypeId;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();
        }

        // Create sub categories
        if ($isSubCategoryRequest) {
            $data = [];
            foreach ($request->post('sub-category-requests') as $subCategoryName) {
                !is_null($subCategoryName) && array_push($data, [
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            $requestedSubCategories = $category->subCategories()->createMany($data);
        }

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $thana = Thana::find($request->post('thana'));
        $union = !$isThanaRequest ? Union::find($request->post('union')) : null;

        if ($isThanaRequest) {
            $thana = new Thana;
            $thana->district_id = $request->post('district');
            $thana->bn_name = $request->post('thana-request');
            $thana->is_pending = 1;
            $thana->save();
        }

        if ($isUnionRequest) {
            $union = new Union;
            $union->thana_id = $thana->id;
            $union->bn_name = $request->post('union-request');
            $union->is_pending = 1;
            $union->save();
        }

        $org = new Org;
        $org->user_id = $user->id;
        $org->division_id = $request->post('division');
        $org->district_id = $request->post('district');
        $org->thana_id = $thana->id;
        $org->union_id = $union->id;

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
        $org->subCategories()->saveMany($requestedSubCategories);

        // User
        $user->email = $request->post('personal-email');
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
        $requestedSubCategories = [];

        // Create categories
        if ($isCategoryRequest && $previousCategory->is_confirmed == 0) {
            $category = $org->category;
            $org->category()->update(['name' => $request->post('category-request')]);
        } else if ($isCategoryRequest && $previousCategory->is_confirmed == 1) {
            $serviceTypeId = ServiceType::getThe('org')->id;
            $category = new Category;
            $category->service_type_id = $serviceTypeId;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();
        }

        // Create sub categories
        if ($isSubCategoryRequest) {
            $data = [];
            foreach ($request->post('sub-category-requests') as $subCategoryName) {
                !is_null($subCategoryName) && array_push($data, [
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            $requestedSubCategories = $category->subCategories()->createMany($data);
        }

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $previousThana = $org->thana;
        $previousUnion = $org->union;
        $thana = Thana::find($request->post('thana'));
        $union = !$isThanaRequest ? Union::find($request->post('union')) : null;

        if ($isThanaRequest) {
            $thana = new Thana;
            $thana->district_id = $request->post('district');
            $thana->bn_name = $request->post('thana-request');
            $thana->is_pending = 1;
            $thana->save();
        }

        if ($isUnionRequest) {
            $union = new Union;
            $union->thana_id = $thana->id;
            $union->bn_name = $request->post('union-request');
            $union->is_pending = 1;
            $union->save();
        }

        $org->division_id = $request->post('division');
        $org->district_id = $request->post('district');
        $org->thana_id = $thana->id;
        $org->union_id = $union->id;
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
        $previousRequested = $org->subCategories('requested');
        $org->subCategories()->detach();
        $previousRequested->delete();
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
        $org->subCategories()->saveMany($requestedSubCategories);

        // User
        $user->email = $request->post('personal-email');
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
        $org = Org::with(['division', 'district', 'thana', 'union'])->find($id);

        if ($org->user_id != Auth::id() && $org->is_pending == 0) {
            return redirect(route('organization-service-registration.index'));
        }

        $categories = Category::getAll('org')->get();
        $subCategories = SubCategory::getAll('org')->get();
        $divisions = Division::all();
        $districts = $org->division()->with('districts')->first()->districts;
        $thanas = $org->district->thanas()->where('is_pending', 0)->get();
        $unions = $org->thana->unions()->where('is_pending', 0)->get();

        $isPicExists = $org->user->photo;
        return view('frontend.registration.org-service.edit', compact('org', 'isPicExists', 'workMethods', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditPendingOrgService;
use App\Http\Requests\StoreOrg;
use App\Http\Requests\StorePendingOrgService;
use App\Http\Requests\UpdateOrg;
use App\Models\Category;
use App\Models\Org;
Use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceDoc;
use App\Models\PendingOrgServiceImage;
use App\Models\ServiceType;
use App\Models\SubCategory;
use App\Models\WorkMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $orgs = $user->orgs('pending')->get();

        $categories = Category::getAll('org')->get();
        $subCategories = SubCategory::getAll('org')->get();
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'orgs', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');
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
            $subCategories = SubCategory::getAll('org')->get();
            $districts = District::take(20)->get();
            $thanas = Thana::take(20)->get();
            $unions = Union::take(20)->get();
            $classesToAdd = ['active', 'disabled'];
            $isPicExists = $user->photo;
            $compact = compact('classesToAdd', 'orgs', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');
            return view('frontend.registration.org-service.confirm', $compact);
        }

        // handle category  and sub-category request
        $category = Category::find($request->post('category'));
        $subCategories = $subCategories = SubCategory::all()->whereIn('id', $request->post('sub-categories'));
        $requestedSubCategories = [];
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';

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

        $org = new Org;
        $org->user_id = $user->id;
        $org->district_id = $request->post('district');
        $org->thana_id = $request->post('thana');
        $org->union_id = $request->post('union');

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
            $org->trade_license = $request->file('trade-license')->store('ind/' . $org->id . '/' . 'docs');
        }
        $org->save();

        // associate sub-categories
        $org->subCategories()->saveMany($subCategories);
        $org->subCategories()->saveMany($requestedSubCategories);

        // User
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();

        // work images
        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, ['path' => $image->store('org/' . $org->id . '/' . 'images')]);
            }
            $org->workImages()->createMany($images);
        }

        DB::commit();

        return back()->with('success', 'ধন্যবাদ! আমরা আপনার অনুরোধ যত তাড়াতাড়ি সম্ভব পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
    }


    public function update(UpdateOrg $request, $id)
    {

        DB::beginTransaction();

        $user = Auth::user();
        $org = Org::find($id);

        // handle category  and sub-category request
        $category = Category::find($request->post('category'));
        $subCategories = $subCategories = SubCategory::all()->whereIn('id', $request->post('sub-categories'));;
        $requestedSubCategories = [];
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';

        // Create categories
        if ($isCategoryRequest) {
            $serviceTypeId = ServiceType::getThe('ind')->id;
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

        $org->district_id = $request->post('district');
        $org->thana_id = $request->post('thana');
        $org->union_id = $request->post('union');
        $org->name = $request->post('name');
        $org->description = $request->post('description');
        $org->mobile = $request->post('mobile');
        $org->email = $request->post('email');
        $org->category_id = $category->id;
        $org->website = $request->post('website');
        $org->facebook = $request->post('facebook');
        $org->address = $request->post('address');
        $org->save();

        if ($request->hasFile('trade-license')) {
            $org->trade_license = $request->file('trade-license')->store('ind/' . $org->id . '/' . 'docs');
        }
        $org->save();

        // sub-categories
        $previousRequested = $org->subCategories('requested');
        $org->subCategories()->detach();
        $previousRequested->delete();

        // associate sub-categories
        $org->subCategories()->saveMany($subCategories);
        $org->subCategories()->saveMany($requestedSubCategories);

        // User
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();

        // work images
        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, ['path' => $image->store('org/' . $org->id . '/' . 'images')]);
            }
            $org->workImages()->createMany($images);
        }

        DB::commit();

        return back()->with('success', 'Done!');
    }

    public function edit($id)
    {
        $org = Org::find($id);
        $categories = Category::getAll('org')->get();
        $subCategories = SubCategory::getAll('org')->get();
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();

        $isPicExists = $org->user->photo;
        return view('frontend.registration.org-service.edit', compact('org', 'isPicExists', 'workMethods', 'categories', 'subCategories', 'districts', 'thanas', 'unions'));
    }
}

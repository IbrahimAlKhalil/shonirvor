<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\WorkMethod;
use App\Models\ServiceType;
use App\Models\SubCategory;
use App\Http\Requests\StoreInd;
use App\Http\Requests\UpdateInd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;

class IndServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $inds = $user->inds('pending')->get();

        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind')->get();
        // TODO:: Don't pass all the subcategories, districts, thanas, unions after implementing ajax
        $divisions = Division::all();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'inds', 'workMethods', 'divisions', 'isPicExists', 'categories');
        $view = 'frontend.registration.ind-service.confirm';
        $count = $inds->count();

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
        unset($compact['inds'], $compact['classesToAdd']);

        return view('frontend.registration.ind-service.index', $compact);
    }


    public function store(StoreInd $request)
    {

        DB::beginTransaction();

        $user = Auth::user();
        $inds = $user->inds('pending')->get();

        // check what if current user didn't reach at the maximum pending request
        if ($inds->count() >= 3) {

            $workMethods = WorkMethod::all();
            $categories = Category::getAll('ind')->get();
            $divisions = Division::all();
            $classesToAdd = ['active', 'disabled'];
            $isPicExists = $user->photo;
            $compact = compact('classesToAdd', 'inds', 'workMethods', 'divisions', 'isPicExists', 'categories');

            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.ind-service.confirm', $compact);
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

        $ind = new Ind;
        $ind->user_id = $user->id;
        $ind->division_id = $request->post('division');
        $ind->district_id = $request->post('district');
        $ind->thana_id = $thana->id;
        $ind->union_id = $union->id;

        $ind->mobile = $request->post('mobile');
        $ind->referrer = $request->post('referrer');
        $ind->email = $request->post('email');
        $ind->category_id = $category->id;
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // associate sub-categories
        !$isCategoryRequest && $ind->subCategories()->saveMany($subCategories);
        $ind->subCategories()->saveMany($requestedSubCategories);

        // ind_work_method table
        // TODO:: Some custom validation will be needed for workmethods

        $workMethods = [];
        foreach ($request->post('work-methods') as $workMethod) {
            array_key_exists('id', $workMethod) && array_push($workMethods, [
                'work_method_id' => $workMethod['id'],
                'ind_id' => $ind->id,
                'rate' => $workMethod['rate'],
                'is_negotiable' => array_key_exists('is-negotiable', $workMethod) && $workMethod['is-negotiable'] == 'on'
            ]);
        }

        DB::table('ind_work_method')->insert($workMethods);

        // User
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->age = $request->post('age');
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
                (array_key_exists('description', $image) && !is_null($image['description'])) && $images[$key]['path'] = $image['file']->store('ind/' . $ind->id . '/' . 'images');
            }

            $ind->workImages()->createMany($images);
        }

        // identities
        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $identity) {
                array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
            }
            DB::table('identities')->insert($identities);
        }

        DB::commit();

        return back()->with('success', 'ধন্যবাদ! আমরা আপনার অনুরোধ যত তাড়াতাড়ি সম্ভব পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
    }

    public function update(UpdateInd $request, $id)
    {

        $user = Auth::user();
        $ind = Ind::find($id);

        // TODO:: Move this validation to a requests class
        if ($ind->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }

        DB::beginTransaction();

        // handle category  and sub-category request
        // TODO:: Do some custom validation for category and subcategory

        $previousCategory = $ind->category;
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';
        $category = Category::find($request->post('category'));
        $subCategories = !$isCategoryRequest ? SubCategory::all()->whereIn('id', $request->post('sub-categories')) : null;
        $requestedSubCategories = [];

        // Create categories
        if ($isCategoryRequest && $previousCategory->is_confirmed == 0) {
            $category = $ind->category;
            $ind->category()->update(['name' => $request->post('category-request')]);
        } else if ($isCategoryRequest && $previousCategory->is_confirmed == 1) {
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

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $previousThana = $ind->thana;
        $previousUnion = $ind->union;
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

        $ind->division_id = $request->post('division');
        $ind->district_id = $request->post('district');
        $ind->thana_id = $thana->id;
        $ind->union_id = $union->id;
        $ind->mobile = $request->post('mobile');
        $ind->email = $request->post('email');
        $ind->category_id = $category->id;
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // delete category and subcategories
        $previousRequested = $ind->subCategories('requested');
        $ind->subCategories()->detach();
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
        !$isCategoryRequest && $ind->subCategories()->saveMany($subCategories);
        $ind->subCategories()->saveMany($requestedSubCategories);

        // ind_work_method table
        $ind->workMethods()->detach();

        $workMethods = [];
        foreach ($request->post('work-methods') as $workMethod) {
            array_key_exists('id', $workMethod) && array_push($workMethods, [
                'work_method_id' => $workMethod['id'],
                'ind_id' => $ind->id,
                'rate' => $workMethod['rate'],
                'is_negotiable' => array_key_exists('is-negotiable', $workMethod) && $workMethod['is-negotiable'] == 'on'
            ]);
        }

        DB::table('ind_work_method')->insert($workMethods);

        // User
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->age = $request->post('age');
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
                (array_key_exists('description', $image) && !is_null($image['description'])) && $images[$key]['path'] = $image['file']->store('ind/' . $ind->id . '/' . 'images');
            }

            $ind->workImages()->createMany($images);
        }

        // identities
        if ($request->hasFile('identities')) {
            $identities = [];
            foreach ($request->file('identities') as $identity) {
                array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
            }
            DB::table('identities')->insert($identities);
        }


        DB::commit();

        return back()->with('success', 'সম্পন্ন!');
    }

    public function edit($id)
    {
        $ind = Ind::with(['division', 'district', 'thana', 'union'])->find($id);

        // TODO:: Move this validation to a requests class
        if ($ind->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }

        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind')->get();
        // TODO:: Don't pass all the subcategories, districts, thanas, unions after implementing ajax
        $subCategories = SubCategory::getAll('ind')->get();
        $divisions = Division::all();
        $districts = $ind->division()->with('districts')->first()->districts;
        $thanas = $ind->district->thanas()->where('is_pending', 0)->get();
        $unions = $ind->thana->unions()->where('is_pending', 0)->get();

        $isPicExists = $ind->user->photo;
        return view('frontend.registration.ind-service.edit', compact('ind', 'isPicExists', 'workMethods', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions'));
    }
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreEditInd;
use App\Http\Requests\StoreInd;
use App\Http\Requests\UpdateInd;
use App\Models\Ind;
use App\Models\ServiceType;
use App\Models\Category;
use App\Models\WorkMethod;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $inds = $user->inds('pending')->get();

        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind');
        $subCategories = SubCategory::getAll('ind');
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'inds', 'workMethods', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');
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

        $user = Auth::user();
        $inds = $user->inds('pending')->get();

        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind');
        $subCategories = SubCategory::getAll('ind');
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'inds', 'workMethods', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');

        // check what if current user didn't reach at the maximum pending request
        if ($inds->count() >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.ind-service.confirm', $compact);
        }

        // handle category  and sub-category request
        $category = Category::find($request->post('category'));
        $categoryId = $category->id;
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';

        if ($isCategoryRequest) {
            $serviceTypeId = ServiceType::getThe('ind')->id;
            $category = new Category;
            $category->service_type_id = $serviceTypeId;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();

            $subCategories = [];
            foreach ($request->post('sub-category-requests') as $subCategoryName) {
                !is_null($subCategoryName) && array_push($subCategories, [
                    'category_id' => $category->id,
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            DB::table('sub_categories')->insert($subCategories);
            $categoryId = $category->id;
        }

        $ind = new Ind;
        $ind->user_id = $user->id;
        $ind->district_id = $request->post('district');
        $ind->thana_id = $request->post('thana');
        $ind->union_id = $request->post('union');

        $ind->mobile = $request->post('mobile');
        $ind->referrer = $request->post('referrer');
        $ind->email = $request->post('email');
        $ind->category_id = $categoryId;
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // sub-categories
        foreach ($category->subCategories as $subCategory) {
            $ind->subCategories()->save($subCategory);
        }

        // ind_work_method table
        $workMethods = [];
        foreach ($request->post('work-methods') as $workMethod) {
            array_push($workMethods, [
                'work_method_id' => $workMethod,
                'ind_id' => $ind->id
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
        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, ['path' => $image->store('ind/' . $ind->id . '/' . 'images')]);
            }
            $ind->workImages()->createMany($images);
        }

        return back()->with('success', 'ধন্যবাদ! আমরা আপনার অনুরোধ যত তাড়াতাড়ি সম্ভব পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
    }

    public function update(UpdateInd $request, $id)
    {

        $user = Auth::user();
        $ind = Ind::find($id);

        // handle category  and sub-category request
        $category = Category::find($request->post('category'));
        $categoryId = $category->id;
        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';

        if ($isCategoryRequest) {
            $ind->category()->delete();

            $serviceTypeId = ServiceType::getThe('ind')->id;
            $category = new Category;
            $category->service_type_id = $serviceTypeId;
            $category->name = $request->post('category-request');
            $category->is_confirmed = 0;
            $category->save();

            $subCategories = [];
            foreach ($request->post('sub-category-requests') as $subCategoryName) {
                !is_null($subCategoryName) && array_push($subCategories, [
                    'category_id' => $category->id,
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            DB::table('sub_categories')->insert($subCategories);
            $categoryId = $category->id;
        }

        $ind->district_id = $request->post('district');
        $ind->thana_id = $request->post('thana');
        $ind->union_id = $request->post('union');
        $ind->mobile = $request->post('mobile');
        $ind->email = $request->post('email');
        $ind->category_id = $categoryId;
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // sub-categories
        foreach ($category->subCategories as $subCategory) {
            $ind->subCategories()->save($subCategory);
        }

        // ind_work_method table
        $ind->workMethods()->each(function ($workMethod) {
            $workMethod->delete();
        });

        $workMethods = [];
        foreach ($request->post('work-methods') as $workMethod) {
            array_push($workMethods, [
                'work_method_id' => $workMethod,
                'ind_id' => $ind->id
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
        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, ['path' => $image->store('ind/' . $ind->id . '/' . 'images')]);
            }
            $ind->workImages()->createMany($images);
        }

        return back()->with('success', 'Done!');
    }

    public function edit($id)
    {
        $ind = Ind::find($id);
        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind');
        $subCategories = SubCategory::getAll('ind');
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();

        $isPicExists = $ind->user->photo;
        return view('frontend.registration.ind-service.edit', compact('ind', 'isPicExists', 'workMethods', 'categories', 'subCategories', 'districts', 'thanas', 'unions'));
    }
}
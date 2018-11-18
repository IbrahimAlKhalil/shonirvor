<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\User;
use App\Models\Thana;
use App\Models\Union;
use App\Models\Income;
use App\Models\Village;
use App\Models\Package;
use App\Models\Division;
use App\Models\Category;
use App\Models\District;
use App\Models\Reference;
use App\Models\WorkMethod;
use App\Models\ServiceType;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Requests\StoreInd;
use App\Http\Requests\UpdateInd;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class IndServiceRegistrationController extends Controller
{
    private $referrer;
    private $referrerInterests;

    public function __construct(Request $request)
    {
        if (Route::currentRouteName() == 'individual-service-registration.store'
            || Route::currentRouteName() == 'individual-service-registration.update') {

            $this->referrer = User::with('referPackage.package.properties')
                ->where('mobile', $request->input('referrer'))
                ->first();

            if ($this->referrer && $this->referrer->referPackage()->exists()) {
                $referrerPackage = $this->referrer->referPackage->package;
            } else {
                $referrerPackage = Package::find(1);
            }

            if ($referrerPackage->properties()->where('name', 'refer_target')->first()->value) {
                // TODO: Do something
            } else {
                $this->referrerInterests['onetime'] = $referrerPackage->properties()->where('name', 'refer_onetime_interest')->first()->value;
                $this->referrerInterests['renew'] = $referrerPackage->properties()->where('name', 'refer_renew_interest')->first()->value;
            }
        }
    }

    public function index()
    {
        $user = Auth::user();
        $inds = $user->inds()->onlyPending();

        if ($inds->exists()) {
            return redirect(route('individual-service-registration.edit', $inds->first()->id));
        }

        $packages = Package::with('properties')->select('id')->where('package_type_id', 1)->get();
        $paymentMethods = PaymentMethod::all();

        $categories = Category::getAll('ind')->get();
        // TODO:: Don't pass all the subcategories, districts, thanas, unions after implementing ajax
        $divisions = Division::all();
        $classesToAdd = ['active', 'disabled'];

        return view('frontend.registration.ind-service.index', compact('classesToAdd', 'inds', 'divisions', 'categories', 'user', 'packages', 'paymentMethods'));
    }

    public function store(StoreInd $request)
    {
        // TODO:: Check what if the user already have an account with the requested category
        // TODO:: User should choose one of the packages made for individual service, validate it.
        // TODO:: Review validation

        DB::beginTransaction();

        $user = Auth::user();

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
            foreach ($request->post('sub-category-requests') as $subCategory) {
                array_key_exists('name', $subCategory) && array_push($data, [
                    'name' => $subCategory['name'],
                    'is_confirmed' => 0
                ]);
            }
            $requestedSubCategories = $category->subCategories()->createMany($data);
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

        $ind = new Ind;
        $ind->user_id = $user->id;
        $ind->division_id = $request->post('division');
        $ind->district_id = $request->post('district');
        $ind->thana_id = $thana;
        $ind->union_id = $union;
        $ind->village_id = $village;

        $ind->description = $request->post('description');
        $ind->mobile = $request->post('mobile');
        $ind->email = $request->post('email');
        $ind->category_id = $category->id;
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->slug = 'sp-' . now();
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }

        if ($request->hasFile('cv')) {
            $ind->cv = $request->file('cv')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // Create reference
        if ($request->filled('referrer')) {
            $referrer = new Reference;
            $referrer->user_id = $this->referrer->id;
            $referrer->service_id = $ind->id;
            $referrer->service_type_id = 1;
            $referrer->onetime_interest = $this->referrerInterests['onetime'];
            $referrer->renew_interest = $this->referrerInterests['renew'];
            $referrer->save();
        }

        // associate sub-categories
        $data = [];

        if (!$isCategoryRequest) {
            foreach ($subCategories as $subCategory) {
                array_push($data, [
                    'sub_category_id' => $subCategory->id,
                    'sub_categoriable_id' => $ind->id,
                    'sub_categoriable_type' => 'ind'
                ]);
            }
        }

        foreach ($requestedSubCategories as $subCategory) {
            array_push($data, [
                'sub_category_id' => $subCategory->id,
                'sub_categoriable_id' => $ind->id,
                'sub_categoriable_type' => 'ind'
            ]);
        }

        DB::table('sub_categoriables')->insert($data);


        // payment
        // TODO:: Validation
        if ($request->filled('transaction-id')) {
            $payment = new Income;
            $payment->package_id = $request->post('package');
            $payment->payment_method_id = $request->post('payment-method');
            $payment->from = $request->post('from');
            $payment->transactionId = $request->post('transaction-id');
            $ind->payments()->save($payment);
        }


        // ind_work_method table
        // TODO:: Some custom validation will be needed for workmethods

        $workMethods = [];
        // sub category rates
        if (!$isCategoryRequest) {
            foreach ($request->post('sub-category-rates') as $subCategoryRate) {
                if (array_key_exists('id', $subCategoryRate)) {
                    foreach ($subCategoryRate['work-methods'] as $workMethod) {
                        if (array_key_exists('checkbox', $workMethod) && $workMethod['checkbox'] == 'on') {
                            array_push($workMethods, [
                                'work_method_id' => $workMethod['id'],
                                'ind_id' => $ind->id,
                                'sub_category_id' => $subCategoryRate['id'],
                                'rate' => array_key_exists('rate', $workMethod) ? $workMethod['rate'] : null
                            ]);
                        }
                    }
                }
            }
        }
        // requested subcategory rates
        if ($isSubCategoryRequest) {
            $count = 0;
            foreach ($request->post('sub-category-requests') as $index => $subCategoryRate) {
                if (array_key_exists('name', $subCategoryRate)) {
                    foreach ($subCategoryRate['work-methods'] as $workMethod) {
                        if (array_key_exists('checkbox', $workMethod) && $workMethod['checkbox'] == 'on') {
                            array_push($workMethods, [
                                'work_method_id' => $workMethod['id'],
                                'ind_id' => $ind->id,
                                'sub_category_id' => $requestedSubCategories[$count]->id,
                                'rate' => array_key_exists('rate', $workMethod) ? $workMethod['rate'] : null
                            ]);
                        }
                    }
                }
                $count++;
            }
        }

        DB::table('ind_work_method')->insert($workMethods);

        // User
        if (!$user->nid && $request->has('nid')) {
            $user->nid = $request->post('nid');
        }


        $user->dob = $request->post('year') . '-' . $request->post('month') . '-' . $request->post('day');

        $user->qualification = $request->post('qualification');
        $user->save();

        // work images
        if ($request->file('images')) {
            $files = $request->file('images');
            $images = [];
            // TODO:: Validation

            foreach ($files as $image) {
                array_push($images, [
                    'path' => $image['file']->store('ind/' . $ind->id . '/' . 'images'),
                ]);
            }

            foreach ($request->post('images') as $key => $image) {
                if (array_key_exists('description', $image) && !is_null($image['description'])) {
                    if (isset($images[$key])) {
                        $images[$key]['description'] = $image['description'];
                    }
                }
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

        return redirect(route('individual-service-registration.edit', $ind->id))->with('success', 'ধন্যবাদ! আমরা আপনার অনুরোধ যত তাড়াতাড়ি সম্ভব পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
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
            foreach ($request->post('sub-category-requests') as $subCategory) {
                array_key_exists('name', $subCategory) && array_push($data, [
                    'name' => $subCategory['name'],
                    'is_confirmed' => 0
                ]);
            }
            $requestedSubCategories = $category->subCategories()->createMany($data);
        }

        // handle thana and union request
        // TODO:: Do some custom validation for thana and union
        $isThanaRequest = $request->has('no-thana') && $request->post('no-thana') == 'on';
        $isUnionRequest = $request->has('no-union') && $request->post('no-union') == 'on';
        $isVillageRequest = $request->has('no-village') && $request->post('no-village') == 'on';
        $thana = $request->post('thana');
        $union = $request->post('union');
        $village = $request->post('village');
        $previousThana = $ind->thana;
        $previousUnion = $ind->union;
        $previousVillage = $ind->village;

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

        $ind->division_id = $request->post('division');
        $ind->district_id = $request->post('district');
        $ind->thana_id = $thana;
        $ind->union_id = $union;
        $ind->village_id = $village;
        $ind->category_id = $category->id;
        $ind->description = $request->post('description');
        $ind->mobile = $request->post('mobile');
        $ind->email = $request->post('email');
        $ind->website = $request->post('website');
        $ind->facebook = $request->post('facebook');
        $ind->address = $request->post('address');
        $ind->save();
        if ($request->hasFile('experience-certificate')) {
            $ind->experience_certificate = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
        }

        if ($request->hasFile('cv')) {
            $ind->cv = $request->file('cv')->store('ind/' . $ind->id . '/' . 'docs');
        }
        $ind->save();

        // delete category and subcategories
        $ind->workMethods()->detach();
        $previousRequested = $ind->subCategories('requested');
        $ind->subCategories()->detach();
        $previousRequested->delete();
        if (!$isCategoryRequest && $previousCategory->is_confirmed = 0) {
            $previousCategory->delete();
        }

        if (!$isVillageRequest && $previousVillage->is_pending == 1) {
            $previousVillage->delete();
        }

        if (!$isUnionRequest && $previousUnion->is_pending == 1) {
            $previousUnion->delete();
        }

        if (!$isThanaRequest && $previousThana->is_pending == 1) {
            $previousThana->delete();
        }

        // associate sub-categories
        $data = [];

        if (!$isCategoryRequest) {
            foreach ($subCategories as $subCategory) {
                array_push($data, [
                    'sub_category_id' => $subCategory->id,
                    'sub_categoriable_id' => $ind->id,
                    'sub_categoriable_type' => 'ind'
                ]);
            }
        }

        foreach ($requestedSubCategories as $subCategory) {
            array_push($data, [
                'sub_category_id' => $subCategory->id,
                'sub_categoriable_id' => $ind->id,
                'sub_categoriable_type' => 'ind'
            ]);
        }

        DB::table('sub_categoriables')->insert($data);


        // payment
        $ind->payments()->delete();
        if ($request->filled('transaction-id')) {
            $payment = new Income;
            $payment->package_id = $request->post('package');
            $payment->payment_method_id = $request->post('payment-method');
            $payment->from = $request->post('from');
            $payment->transactionId = $request->post('transaction-id');
            $ind->payments()->save($payment);
        }


        // ind_work_method table

        $workMethods = [];
        // sub category rates
        if (!$isCategoryRequest) {
            foreach ($request->post('sub-category-rates') as $subCategoryRate) {
                if (array_key_exists('id', $subCategoryRate)) {
                    foreach ($subCategoryRate['work-methods'] as $workMethod) {
                        if (array_key_exists('checkbox', $workMethod) && $workMethod['checkbox'] == 'on') {
                            array_push($workMethods, [
                                'work_method_id' => $workMethod['id'],
                                'ind_id' => $ind->id,
                                'sub_category_id' => $subCategoryRate['id'],
                                'rate' => array_key_exists('rate', $workMethod) ? $workMethod['rate'] : null
                            ]);
                        }
                    }
                }
            }
        }
        // requested subcategory rates
        if ($isSubCategoryRequest) {
            foreach ($request->post('sub-category-requests') as $index => $subCategoryRate) {
                if (array_key_exists('name', $subCategoryRate)) {
                    foreach ($subCategoryRate['work-methods'] as $workMethod) {
                        if (array_key_exists('checkbox', $workMethod) && $workMethod['checkbox'] == 'on') {
                            array_push($workMethods, [
                                'work_method_id' => $workMethod['id'],
                                'ind_id' => $ind->id,
                                'sub_category_id' => $requestedSubCategories[$index]->id,
                                'rate' => array_key_exists('rate', $workMethod) ? $workMethod['rate'] : null
                            ]);
                        }
                    }
                }
            }
        }

        DB::table('ind_work_method')->insert($workMethods);

        // User
        $user->nid = $request->post('nid');
        $user->dob = $request->post('year') . '-' . $request->post('month') . '-' . $request->post('day');

        $user->qualification = $request->post('qualification');
        $user->save();

        // work images
        if ($request->file('images')) {
            $files = $request->file('images');
            $images = [];
            // TODO:: Validation

            foreach ($files as $image) {
                array_push($images, [
                    'path' => $image['file']->store('ind/' . $ind->id . '/' . 'images'),
                ]);
            }

            foreach ($request->post('images') as $key => $image) {
                if (array_key_exists('description', $image) && !is_null($image['description'])) {
                    if (isset($images[$key])) {
                        $images[$key]['description'] = $image['description'];
                    }
                }
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

    public function edit(Ind $ind)
    {
        $ind->load(['referredBy.user', 'division', 'district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods', 'user', 'payments']);

        // TODO:: Move this validation to a requests class

        if ($ind->user_id != Auth::id() || !is_null($ind->expire)) {
            return redirect(route('individual-service-registration.index'));
        }

        $categories = Category::whereServiceTypeId(1)->whereIsConfirmed(1)->get();
        $subCategories = SubCategory::whereCategoryId($ind->category_id)->whereIsConfirmed(1)->get();
        $indSubCategories = $ind->subCategories()->whereIsConfirmed(1)->get();
        $pendingSubCategories = $ind->subCategories()->whereIsConfirmed(0)->get();
        $divisions = Division::all();
        $districts = District::whereDivisionId($ind->division_id)->get();
        $thanas = $ind->district->thanas()->whereIsPending(0)->get();
        $unions = $ind->thana->unions()->whereIsPending(0)->get();
        $villages = Village::whereUnionId($ind->union->id)->whereIsPending(0)->get();
        $indWorkMethods = $ind->workMethods->groupBy('pivot.sub_category_id');
        $workMethods = WorkMethod::all();
        $packages = Package::with('properties')->select('id')->where('package_type_id', 1)->get();
        $paymentMethods = PaymentMethod::all();


        return view('frontend.registration.ind-service.edit', compact('ind', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions', 'villages', 'workMethods', 'indWorkMethods', 'indSubCategories', 'pendingSubCategories', 'user', 'canEditNid', 'packages', 'paymentMethods', 'paymentMethods'));
    }
}
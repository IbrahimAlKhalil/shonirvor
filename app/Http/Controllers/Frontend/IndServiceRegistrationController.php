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
    public function index()
    {
        $user = Auth::user();
        $inds = $user->inds()->onlyPending();

        $hasAccount = $user->inds()->onlyApproved()->exists() || $user->orgs()->onlyApproved()->exists();

        if ($inds->exists()) {
            return redirect(route('individual-service-registration.edit', $inds->first()->id));
        }

        $packages = Package::with('properties')->select('id')->where('package_type_id', 1)->get();
        $paymentMethods = PaymentMethod::all();

        $categoryIds = $user->inds()->pluck('id')->toArray();

        $categories = Category::onlyInd()->onlyConfirmed()->whereNotIn('id', $categoryIds)->get();
        $divisions = Division::all();
        $classesToAdd = ['active', 'disabled'];

        return view('frontend.registration.ind-service.index', compact('classesToAdd', 'inds', 'divisions', 'categories', 'user', 'packages', 'paymentMethods', 'hasAccount'));
    }

    public function store(StoreInd $request)
    {
        // TODO:: User should choose at least one sub-caegory
        // TODO:: Review validation

        DB::beginTransaction();

        $user = Auth::user();

        // handle category  and sub-category request
        // TODO:: Validate that selected sub-categories belong to the selected category

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
        $ind->slug = time();
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

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $reference = new Reference;
            $reference->user_id = $referrer->id;
            $reference->service_id = $ind->id;
            $reference->service_type_id = 1;
            $reference->onetime_interest = $referrerPackage['refer_onetime_interest'][0]->value;
            $reference->renew_interest = $referrerPackage['refer_renew_interest'][0]->value;
            if ($referrerPackage['duration'][0]->value && $referrerPackage['refer_target'][0]->value) {
                $reference->target = $referrerPackage['refer_target'][0]->value;
                $reference->target_start_time = $referrer->referPackage->created_at;
                $reference->target_end_time = $referrer->referPackage->created_at->addDays($referrerPackage['duration'][0]->value);
                $reference->fail_onetime_interest = $referrerPackage['refer_fail_onetime_interest'][0]->value;
                $reference->fail_renew_interest = $referrerPackage['refer_fail_renew_interest'][0]->value;;
            }
            $reference->save();
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
        $payment = new Income;
        $payment->package_id = $request->post('package');
        $payment->payment_method_id = $request->post('payment-method');
        $payment->from = $request->post('from');
        $payment->transactionId = $request->post('transaction-id');

        $freePackageId = Package::onlyInd()->first()->id;
        if ($request->post('package') == $freePackageId) {
            $payment->payment_method_id = null;
            $payment->from = null;
            $payment->transactionId = null;
        }

        $ind->payments()->save($payment);


        // ind_work_method table
        // TODO: Some custom validation will be needed for workmethods

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

        if (!$user->dob) {
            $user->dob = $request->post('year') . '-' . $request->post('month') . '-' . $request->post('day');
        }

        if (!$user->qualification) {
            $user->qualification = $request->post('qualification');
        }

        $user->save();

        // work images
        if ($request->file('images')) {
            $files = $request->file('images');
            $images = [];

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
        if (!($user->inds()->onlyApproved()->exists() || $user->orgs()->onlyApproved()->exists())) {
            if ($request->hasFile('identities')) {
                $identities = [];
                foreach ($request->file('identities') as $identity) {
                    array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
                }
                DB::table('identities')->insert($identities);
            }
        }

        DB::commit();

        return redirect(route('individual-service-registration.edit', $ind->id))->with('success', 'ধন্যবাদ! আপনার অনুরোধটি সাবমিট হয়েছে। যত তাড়াতাড়ি সম্ভব আমরা অনুরোধটি পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
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
        // TODO:: Validate that selected sub-categories belong to the selected category

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
        if ($isSubCategoryRequest && $request->filled('sub-category-requests')) {
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

        // Create reference
        if ($request->filled('referrer')
            && $ind->referredBy
            && $ind->referredBy->user->mobile != $request->input('referrer')) {

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $ind->referredBy->user_id = $referrer->id;
            $ind->referredBy->service_id = $ind->id;
            $ind->referredBy->service_type_id = 1;
            $ind->referredBy->onetime_interest = $referrerPackage['refer_onetime_interest'][0]->value;
            $ind->referredBy->renew_interest = $referrerPackage['refer_renew_interest'][0]->value;
            if ($referrerPackage['duration'][0]->value && $referrerPackage['refer_target'][0]->value) {
                $ind->referredBy->target = $referrerPackage['refer_target'][0]->value;
                $ind->referredBy->target_start_time = $referrer->referPackage->created_at;
                $ind->referredBy->target_end_time = $referrer->referPackage->created_at->addDays($referrerPackage['duration'][0]->value);
                $ind->referredBy->fail_onetime_interest = $referrerPackage['refer_fail_onetime_interest'][0]->value;
                $ind->referredBy->fail_renew_interest = $referrerPackage['refer_fail_renew_interest'][0]->value;;
            } else {
                $ind->referredBy->target = null;
                $ind->referredBy->target_start_time = null;
                $ind->referredBy->target_end_time = null;
                $ind->referredBy->fail_onetime_interest = null;
                $ind->referredBy->fail_renew_interest = null;
            }
            $ind->referredBy->save();
        }
        elseif ($request->filled('referrer') && ! $ind->referredBy) {

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $reference = new Reference;
            $reference->user_id = $referrer->id;
            $reference->service_id = $ind->id;
            $reference->service_type_id = 1;
            $reference->onetime_interest = $referrerPackage['refer_onetime_interest'][0]->value;
            $reference->renew_interest = $referrerPackage['refer_renew_interest'][0]->value;
            if ($referrerPackage['duration'][0]->value && $referrerPackage['refer_target'][0]->value) {
                $reference->target = $referrerPackage['refer_target'][0]->value;
                $reference->target_start_time = $referrer->referPackage->created_at;
                $reference->target_end_time = $referrer->referPackage->created_at->addDays($referrerPackage['duration'][0]->value);
                $reference->fail_onetime_interest = $referrerPackage['refer_fail_onetime_interest'][0]->value;
                $reference->fail_renew_interest = $referrerPackage['refer_fail_renew_interest'][0]->value;;
            }
            $reference->save();
        }
        elseif (! $request->filled('referrer') && $ind->referredBy) {
            $ind->referredBy->delete();
        }

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
        $payment = new Income;
        $payment->package_id = $request->post('package');
        $payment->payment_method_id = $request->post('payment-method');
        $payment->from = $request->post('from');
        $payment->transactionId = $request->post('transaction-id');

        $freePackageId = Package::onlyInd()->first()->id;
        if ($request->post('package') == $freePackageId) {
            $payment->payment_method_id = null;
            $payment->from = null;
            $payment->transactionId = null;
        }

        $ind->payments()->save($payment);


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
        if ($isSubCategoryRequest && $request->filled('sub-category-requests')) {
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
        if (!$user->nid && $request->has('nid')) {
            $user->nid = $request->post('nid');
        }

        if (!$user->dob) {
            $user->dob = $request->post('year') . '-' . $request->post('month') . '-' . $request->post('day');
        }

        if (!$user->qualification) {
            $user->qualification = $request->post('qualification');
        }
        $user->save();

        // work images
        if ($request->file('images')) {
            // TODO: Delete previous images
            $files = $request->file('images');
            $images = [];
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
        if (!$user->nid) {
            if ($request->hasFile('identities')) {
                $identities = [];
                foreach ($request->file('identities') as $identity) {
                    array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
                }
                DB::table('identities')->insert($identities);
            }
        }


        DB::commit();

        return back()->with('success', 'রিকোয়েস্টটি এডিট হয়েছে!');
    }

    public function edit(Ind $ind)
    {
        $ind->load(['referredBy.user', 'workImages', 'division', 'district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods', 'user.identities', 'payments']);

        // TODO:: Move this validation to a requests class

        if ($ind->user_id != Auth::id() || !is_null($ind->expire)) {
            return redirect(route('individual-service-registration.index'));
        }

        $user = Auth::user();
        $first = !$user->inds()->onlyApproved()->exists() && !$user->orgs()->onlyApproved()->exists();

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

        return view('frontend.registration.ind-service.edit', compact('ind', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions', 'villages', 'workMethods', 'indWorkMethods', 'indSubCategories', 'pendingSubCategories', 'user', 'canEditNid', 'packages', 'paymentMethods', 'paymentMethods', 'first'));
    }
}
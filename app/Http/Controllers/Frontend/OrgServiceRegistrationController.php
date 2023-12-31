<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Income;
use App\Models\Org;
use App\Models\Category;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Models\Reference;
use App\Models\SubCategory;
use App\Http\Requests\StoreOrg;
use App\Http\Requests\UpdateOrg;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\Thana;
use App\Models\Union;
use Illuminate\Support\Facades\Storage;
use App\Models\Division;
use App\Models\District;

class OrgServiceRegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orgs = $user->orgs()->onlyPending();
        if ($user->inds()->onlyPending()->exists()) {
            return redirect(route('service-registration-instruction'));
        }
        if ($orgs->exists()) {
            return redirect(route('organization-service-registration.edit', $orgs->first()->id));
        }

        $hasAccount = $user->orgs()->onlyApproved()->exists() || $user->orgs()->onlyApproved()->exists();
        $packages = Package::with('properties')->select('id')->where('package_type_id', 2)->get()->sort(function ($a, $b) {
            $aProperties = $a->properties->groupBy('name');
            $bProperties = $b->properties->groupBy('name');

            return $aProperties['duration'][0]->value > $bProperties['duration'][0]->value;
        });
        $paymentMethods = PaymentMethod::all();
        $categoryIds = $user->orgs()->pluck('id')->toArray();

        $categories = Category::onlyOrg()->onlyConfirmed()->whereNotIn('id', $categoryIds)->get();
        $divisions = Division::all();
        $classesToAdd = ['active', 'disabled'];
        $identityExists = $user->identities()->exists();

        // user didn't make any request for being organizational service provider
        return view('frontend.registration.org-service.index', compact('classesToAdd', 'orgs', 'divisions', 'categories', 'user', 'packages', 'paymentMethods', 'hasAccount', 'identityExists'));
    }

    public function store(StoreOrg $request)
    {
        DB::beginTransaction();

        $user = Auth::user();
        if ($user->inds()->onlyPending()->exists()) {
            return redirect(route('service-registration-instruction', 'You have already a pending request'));
        }
        // handle category  and sub-category request

        $isCategoryRequest = $request->has('no-category') && $request->post('no-category') == 'on';
        $isSubCategoryRequest = $request->has('no-sub-category') && $request->post('no-sub-category') == 'on';
        $category = Category::find($request->post('category'));
        $subCategories = !$isCategoryRequest ? SubCategory::whereIn('id', array_map(function ($item) {
            return $item['id'];
        }, $request->has('sub-categories') ? $request->get('sub-categories') : []))->get() : null;

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
        $org->category_id = $category->id;

        $org->name = $request->post('name');
        $org->description = $request->post('description');
        $org->mobile = $request->post('mobile');
        $org->email = $request->post('email');
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
        if ($request->hasFile('cover-photo')) {
            $org->cover_photo = $request->file('cover-photo')->store('org/' . $org->id);
        }
        $org->save();

        // Slug

        $org->slug()->create([
            'sluggable_id' => $org->id,
            'sluggable_type' => 'org',
            'name' => $request->post('slug'),
        ]);

        // Create reference
        if ($request->filled('referrer')) {

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $reference = new Reference;
            $reference->user_id = $referrer->id;
            $reference->service_id = $org->id;
            $reference->service_type_id = 2;
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

        // payment
        $payment = new Income;
        $payment->package_id = $request->post('package');
        $payment->payment_method_id = $request->post('payment-method');
        $payment->from = $request->post('from');
        $payment->transactionId = $request->post('transaction-id');

        $freePackageId = Package::onlyOrg()->first()->id;
        if ($request->post('package') == $freePackageId) {
            $payment->payment_method_id = null;
            $payment->from = null;
            $payment->transactionId = null;
        }

        $org->payments()->save($payment);

        // associate sub-categories$org
        !$isCategoryRequest && $org->subCategories()->saveMany($subCategories);

        $data = [];
        if ($request->has('sub-categories')) {
            foreach ($request->post('sub-categories') as $subCategory) {
                if (isset($subCategory['id']) && !is_null($subCategory['id'])) {
                    array_push($data, [
                        'org_id' => $org->id,
                        'sub_category_id' => $subCategory['id'],
                        'rate' => $subCategory['rate']
                    ]);
                }
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


        // org additional price
        $data = [];
        foreach ($request->post('additional-pricing') as $price) {
            if (isset($price['name']) && isset($price['info'])) {
                array_push($data, [
                    'org_id' => $org->id,
                    'name' => $price['name'],
                    'info' => $price['info']
                ]);
            }
        }

        DB::table('org_additional_prices')->insert($data);


        // User
        if (!$user->nid) {
            $user->nid = $request->post('nid');
        }
        $user->save();

        // work images
        if ($request->file('images')) {
            $count = 0;
            $files = $request->file('images');
            $images = [];

            foreach ($files as $image) {
                if ($count >= 4) break;
                $count++;
                array_push($images, [
                    'path' => $image['file']->store('org/' . $org->id . '/' . 'images'),
                    'work_imagable_type' => 'org',
                    'work_imagable_id' => $org->id,
                ]);
            }

            foreach ($request->post('images') as $key => $image) {
                if (isset($images[$key])) {
                    $description = isset($image['description']) ? $image : null;
                    $images[$key]['description'] = $description;
                }
            }

            DB::table('work_images')->insert($images);
        }

        // identities
        if (!$user->identities()->exists()) {
            if ($request->hasFile('identities')) {
                $identities = [];
                foreach ($request->file('identities') as $orgex => $identity) {
                    if ($orgex > 1) break;
                    array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
                }
                DB::table('identities')->insert($identities);
            }
        }

        DB::commit();

        return redirect(route('organization-service-registration.edit', $org->id))->with('success', 'ধন্যবাদ! আপনার অনুরোধটি সাবমিট হয়েছে। যত তাড়াতাড়ি সম্ভব আমরা অনুরোধটি পর্যালোচনা করব, তাই সঙ্গে থাকুন!');
    }

    public function update(UpdateOrg $request, $id)
    {
        $user = Auth::user();
        $org = Org::find($id);

        // TODO:: Move this validation to a requests class
        if ($org->user_id != Auth::id()) {
            return redirect(route('organization-service-registration.index', 'This is not your service'));
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
        $org->category_id = $category->id;
        $org->name = $request->post('name');
        $org->description = $request->post('description');
        $org->mobile = $request->post('mobile');
        $org->email = $request->post('email');
        $org->website = $request->post('website');
        $org->facebook = $request->post('facebook');
        $org->address = $request->post('address');
        $org->slug()->update([
            'name' => $request->post('slug')
        ]);

        if ($request->hasFile('trade-license')) {
            // delete old file
            Storage::delete($org->trade_license);
            $org->trade_license = $request->file('trade-license')->store('org/' . $org->id . '/' . 'docs');
        }
        if ($request->hasFile('logo')) {
            // delete old file
            Storage::delete($org->logo);
            $org->logo = $request->file('logo')->store('org/' . $org->id);
        }
        if ($request->hasFile('cover-photo')) {
            // delete old file
            Storage::delete($org->cover_photo);
            $org->cover_photo = $request->file('cover-photo')->store('org/' . $org->id);
        }
        $org->save();

        // Create reference
        if ($request->filled('referrer')
            && $org->referredBy
            && $org->referredBy->user->mobile != $request->input('referrer')) {

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $org->referredBy->user_id = $referrer->id;
            $org->referredBy->service_id = $org->id;
            $org->referredBy->service_type_id = 2;
            $org->referredBy->onetime_interest = $referrerPackage['refer_onetime_interest'][0]->value;
            $org->referredBy->renew_interest = $referrerPackage['refer_renew_interest'][0]->value;
            if ($referrerPackage['duration'][0]->value && $referrerPackage['refer_target'][0]->value) {
                $org->referredBy->target = $referrerPackage['refer_target'][0]->value;
                $org->referredBy->target_start_time = $referrer->referPackage->created_at;
                $org->referredBy->target_end_time = $referrer->referPackage->created_at->addDays($referrerPackage['duration'][0]->value);
                $org->referredBy->fail_onetime_interest = $referrerPackage['refer_fail_onetime_interest'][0]->value;
                $org->referredBy->fail_renew_interest = $referrerPackage['refer_fail_renew_interest'][0]->value;;
            } else {
                $org->referredBy->target = null;
                $org->referredBy->target_start_time = null;
                $org->referredBy->target_end_time = null;
                $org->referredBy->fail_onetime_interest = null;
                $org->referredBy->fail_renew_interest = null;
            }
            $org->referredBy->save();
        } elseif ($request->filled('referrer') && !$org->referredBy) {

            $referrer = User::with('referPackage')
                ->where('mobile', $request->input('referrer'))
                ->first();

            $referrerPackage = userReferrerPackage($referrer)->properties->groupBy('name');

            $reference = new Reference;
            $reference->user_id = $referrer->id;
            $reference->service_id = $org->id;
            $reference->service_type_id = 2;
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
        } elseif (!$request->filled('referrer') && $org->referredBy) {
            $org->referredBy->delete();
        }

        // delete category and subcategories
        DB::table('org_sub_category_rates')->where('org_id', $org->id)->delete();
        $previousRequested = $org->subCategories();
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
        $data = [];
        if (!$isCategoryRequest) {
            foreach ($subCategories as $subCategory) {
                array_push($data, [
                    'sub_category_id' => $subCategory->id,
                    'sub_categoriable_id' => $org->id,
                    'sub_categoriable_type' => 'org'
                ]);
            }
        }

        $data = [];
        if ($request->has('sub-categories')) {
            foreach ($request->post('sub-categories') as $subCategory) {
                if (isset($subCategory['id']) && !is_null($subCategory['id'])) {
                    array_push($data, [
                        'org_id' => $org->id,
                        'sub_category_id' => $subCategory['id'],
                        'rate' => $subCategory['rate']
                    ]);
                }
            }
        }

        // Create sub categories
        if ($isSubCategoryRequest) {
            foreach ($request->post('sub-category-requests') as $subCategory) {
                if (isset($subCategory['name'])) {
                    $newSubCategory = new SubCategory;
                    $newSubCategory->category_id = $category->id;
                    $newSubCategory->is_confirmed = 0;
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


        // org additional price
        DB::table('org_additional_prices')->where('org_id', $org->id)->delete();
        $data = [];
        if ($request->has('additional-pricing')) {
            foreach ($request->post('additional-pricing') as $price) {
                if (isset($price['name']) && isset($price['info'])) {
                    array_push($data, [
                        'org_id' => $org->id,
                        'name' => $price['name'],
                        'info' => $price['info']
                    ]);
                }
            }
        }

        DB::table('org_additional_prices')->insert($data);

        // payment
        $org->payments()->delete();
        $payment = new Income;
        $payment->package_id = $request->post('package');
        $payment->payment_method_id = $request->post('payment-method');
        $payment->from = $request->post('from');
        $payment->transactionId = $request->post('transaction-id');

        $freePackageId = Package::onlyOrg()->first()->id;
        if ($request->post('package') == $freePackageId) {
            $payment->payment_method_id = null;
            $payment->from = null;
            $payment->transactionId = null;
        }

        $org->payments()->save($payment);

        // User
        if (!$user->nid) {
            $user->nid = $request->post('nid');
        }
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();

        // work images
        if ($request->file('images')) {
            // TODO: Delete previous images
            $files = $request->file('images');
            $images = [];
            foreach ($files as $image) {
                array_push($images, [
                    'path' => $image['file']->store('org/' . $org->id . '/' . 'images'),
                ]);
            }

            foreach ($request->post('images') as $key => $image) {
                if (array_key_exists('description', $image) && !is_null($image['description'])) {
                    if (isset($images[$key])) {
                        $images[$key]['description'] = $image['description'];
                    }
                }
            }

            $org->workImages()->createMany($images);
        }

        // identities
        if (!$user->nid) {
            if ($request->hasFile('identities')) {
                $identities = [];
                foreach ($request->file('identities') as $orgex => $identity) {
                    if ($orgex > 1) break;
                    array_push($identities, ['path' => $identity->store('user-photos/' . $user->id), 'user_id' => $user->id]);
                }

                DB::table('identities')->insert($identities);
            }
        }

        DB::commit();

        return back()->with('success', 'রিকোয়েস্টটি এডিট হয়েছে!');
    }

    public function edit($id)
    {
        $org = Org::with(['referredBy.user', 'workImages', 'division', 'district', 'thana', 'union', 'subCategoryRates', 'user.identities', 'slug'])->findOrFail($id);

        if ($org->user_id != Auth::id() || !is_null($org->expire)) {
            return redirect(route('organization-service-registration.index'));
        }

        $categories = Category::onlyOrg()->onlyConfirmed()->get();
        $subCategories = SubCategory::where('is_confirmed', 1)->whereCategoryId($org->category_id)->get();
        $orgSubCategories = $org->subCategoryRates;
        $isNoSubCategory = $orgSubCategories->filter(function ($sub) {
            return $sub['is_confirmed'] == 0;
        })->count();

        $user = Auth::user();
        $first = !$user->inds()->onlyApproved()->exists() && !$user->orgs()->onlyApproved()->exists();
        $packages = Package::with('properties')->select('id')->where('package_type_id', 2)->get()->sort(function ($a, $b) {
            $aProperties = $a->properties->groupBy('name');
            $bProperties = $b->properties->groupBy('name');

            return $aProperties['duration'][0]->value > $bProperties['duration'][0]->value;
        });
        $paymentMethods = PaymentMethod::all();
        $divisions = Division::all();
        $districts = District::whereDivisionId($org->division_id)->get();
        $thanas = $org->district->thanas()->whereIsPending(0)->get();
        $unions = $org->thana->unions()->whereIsPending(0)->get();
        $villages = Village::whereUnionId($org->union->id)->whereIsPending(0)->get();
        $selectedPackage = $org->payments()->select('package_id')->first()->package_id;

        return view('frontend.registration.org-service.edit', compact('org','categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions', 'villages', 'orgSubCategories', 'isNoSubCategory', 'packages', 'paymentMethods', 'first', 'selectedPackage', 'user'));
    }

    public function destroy(Org $org)
    {

        DB::beginTransaction();

        $org->load([
            'category',
            'thana',
            'union',
            'village',
            'subCategories' => function ($query) {
                $query->onlyPending();
            }
        ]);

        $category = $org->category;
        $thana = $org->thana;
        $union = $org->union;
        $village = $org->village;

        $org->forceDelete();
        $org->payments()->delete();
        if ($org->referredBy) $org->referredBy->delete();
        $category->is_confirmed == 0 && $category->delete();
        $village->is_pending == 1 && $village->delete();
        $union->is_pending == 1 && $union->delete();
        $thana->is_pending == 1 && $thana->delete();

        // TODO:: Don't forget to delete documents/images

        deleteOrgDocs($org);

        DB::commit();

        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use App\Models\Category;
use App\Models\Package;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = Org::onlyPending()->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();
        return view('backend.org-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show(Org $serviceRequest)
    {
        $serviceRequest->load([
            'referredBy.user',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategoryRates',
            'additionalPrices',
            'user' => function ($query) {
                $query->with('identities');
            },
            'payments' => function ($query) {
                $query->with([
                    'package' => function ($query) {
                        $query->with('properties');
                    },
                    'paymentMethod'
                ]);
            }
        ]);

        $packages = $serviceRequest->payments->first() ? Package::with('properties')->where('package_type_id', 2)->get() : null;
        $navs = $this->navs();
        $thanas = $serviceRequest->thana->is_pending ? Thana::where('district_id', $serviceRequest->district_id)->get() : [];
        $unions = !$serviceRequest->thana->is_pending && $serviceRequest->union->is_pending ? Union::where('thana_id', $serviceRequest->thana_id)->get() : [];
        $villages = !$serviceRequest->union->is_pending && $serviceRequest->village->is_pending ? Village::where('union_id', $serviceRequest->union_id)->get() : [];
        $categories = !$serviceRequest->category->is_confirmed ? Category::onlyInd()->onlyConfirmed()->get() : [];
        return view('backend.org-service-request.show', compact('serviceRequest', 'navs', 'thanas', 'unions', 'villages', 'categories', 'payments', 'packages'));
    }

    public function update(Request $request, Org $serviceRequest)
    {
        // TODO:: Validation needed

        DB::beginTransaction();

        $relations = [
            'category',
            'subCategories' => function ($query) {
                $query->onlyPending();
            }
        ];

        if (!$request->filled('thana')) $thana = Thana::find($request->post('thana'));
        elseif (!$request->filled('thana-request')) array_push($relations, 'thana');

        if (!$request->has('union')) $union = Union::find($request->post('union'));
        elseif (!$request->filled('union-request')) array_push($relations, 'union');

        if (!$request->has('village')) $village = Union::find($request->post('village'));
        elseif (!$request->filled('village-request')) array_push($relations, 'village');

        $serviceRequest->load($relations);

        $category = $request->filled('category') ? Category::find($request->post('category')) : $serviceRequest->category;
        $subCategories = $serviceRequest->subCategories;
        $subCategoryIds = $request->post('sub-categories') ? array_map(function ($element) {
            return $element['id'];
        }, $request->post('sub-categories')) : null;
        $thana = !isset($thana) ? $serviceRequest->thana : $thana;
        $union = !isset($union) ? $serviceRequest->union : $union;
        $village = !isset($village) ? $serviceRequest->village : $village;


        // payments

        DB::table('incomes')->where('id', $request->post('payment'))->update([
            'package_id' => $request->post('package'),
            'approved' => 1
        ]);

        // Update category
        if ($category->is_confirmed == 0) {
            $category->update(['name' => $request->post('category-request'), 'is_confirmed' => 1]);
        } else {
            $serviceRequest->category_id = $category->id;
        }


        // sub category

        if ($request->filled('confirmed-sub-categories')) {
            DB::table('sub_categoriables')->where('sub_categoriable_id', $serviceRequest->id)->whereNotIn('sub_category_id', $request->post('confirmed-sub-categories'))->delete();
            DB::table('org_sub_category_rates')->where('org_id', $serviceRequest->id)->whereNotIn('sub_category_id', $request->post('confirmed-sub-categories'))->delete();
        }

        if ($subCategoryIds) {
            // Delete sub-categories
            foreach ($subCategories as $subCategory) {
                if (!in_array($subCategory->id, $subCategoryIds)) {
                    $serviceRequest->subCategories()->detach($subCategory->id);
                    $subCategory->delete();
                }
            }

            foreach ($request->post('sub-categories') as $subCategoryRequest) {
                $subCategory = $subCategories->first(function ($item) use ($subCategoryRequest) {
                    return $item->id == $subCategoryRequest['id'];
                });

                $subCategory->category_id = $category->id;
                $subCategory->is_confirmed = 1;
                $subCategory->name = $subCategoryRequest['name'];
                $subCategory->save();
            }
        }


        if ($thana->is_pending == 1) {
            $thana->bn_name = $request->post('thana-request');
            $thana->is_pending = 0;
            $thana->save();
        } else {
            $serviceRequest->thana_id = $thana->id;
        }

        if ($union->is_pending == 1) {
            $union->bn_name = $request->post('union-request');
            $union->thana_id = $thana->id;
            $union->is_pending = 0;
            $union->save();
        } else {
            $serviceRequest->union_id = $union->id;
        }

        if ($village->is_pending == 1) {
            $village->bn_name = $request->post('village-request');
            $village->union_id = $union->id;
            $village->is_pending = 0;
            $village->save();
        } else {
            $serviceRequest->village_id = $village->id;
        }

        $duration = Package::with('properties')->find($request->post('package'))->properties->groupBy('name')['duration'][0]->value;

        $serviceRequest->expire = Carbon::today()->addDays($duration)->format('Y-m-d');
        $serviceRequest->save();

        if ($request->has('category-request') && $request->filled('category')) {
            $serviceRequest->category()->delete();
        }

        DB::commit();

        return redirect(route('organization-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy(Org $serviceRequest)
    {
        DB::beginTransaction();
        $category = $serviceRequest->category;
        $thana = $serviceRequest->thana;
        $subCategories = $serviceRequest->subCategories('requested');

        $serviceRequest->subCategories()->detach();
        $subCategories->delete();

        $serviceRequest->forceDelete();
        $category->is_confirmed == 0 && $category->delete();
        $thana->is_confirmed == 0 && $thana->delete();

        // TODO:: Don't forget to delete documents/images

        DB::commit();

        return redirect(route('organization-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('organization-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-edit.index'), 'text' => 'প্রোফাইল এডিট রিকোয়েস্ট']
        ];
    }
}

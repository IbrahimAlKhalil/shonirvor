<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\Package;
use App\Models\SubCategory;
use App\Models\Village;
use App\Models\WorkMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceRequestController extends Controller
{
    public function index()
    {
        $applications = Ind::onlyPending()->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = [
            ['url' => route('backend.request.ind-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('backend.request.top-service.index').'?type=3', 'text' => 'টপ সার্ভিস রিকোয়েস্ট'],
            ['url' => route('dashboard'), 'text' => 'এডিট রিকোয়েস্ট']
        ];

        return view('backend.request.service.ind.index', compact('applications', 'navs'));
    }

    public function show(Ind $application)
    {
        $application->load([
            'referredBy.user',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'workMethods',
            'subCategories',
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

        $packages = $application->payments->first() ? Package::with('properties')->where('package_type_id', 1)->get() : null;

        $workMethods = WorkMethod::all();
        $indWorkMethods = $application->workMethods->groupBy('pivot.sub_category_id');
        $thanas = $application->thana->is_pending ? Thana::where('district_id', $application->district_id)->get() : [];
        $unions = !$application->thana->is_pending && $application->union->is_pending ? Union::where('thana_id', $application->thana_id)->get() : [];
        $villages = !$application->union->is_pending && $application->village->is_pending ? Village::where('union_id', $application->union_id)->get() : [];
        $categories = !$application->category->is_confirmed ? Category::onlyInd()->onlyConfirmed()->get() : [];

        return view('backend.request.service.ind.show', compact('application', 'thanas', 'unions', 'villages', 'categories', 'workMethods', 'indWorkMethods', 'payments', 'packages'));
    }

    public function update(Request $request, Ind $application)
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

        $application->load($relations);

        $category = $request->filled('category') ? Category::find($request->post('category')) : $application->category;
        $subCategories = $application->subCategories;
        $subCategoryIds = $request->post('sub-categories') ? array_map(function ($element) {
            return $element['id'];
        }, $request->post('sub-categories')) : null;
        $thana = !isset($thana) ? $application->thana : $thana;
        $union = !isset($union) ? $application->union : $union;
        $village = !isset($village) ? $application->village : $village;


        // payments

        DB::table('incomes')->where('id', $request->post('payment'))->update([
            'package_id' => $request->post('package'),
            'approved' => 1
        ]);

        // Update category
        if ($category->is_confirmed == 0) {
            $category->update(['name' => $request->post('category-request'), 'is_confirmed' => 1]);
        } else {
            $application->category_id = $category->id;
        }


        // sub category

        if ($request->filled('confirmed-sub-categories')) {
            DB::table('sub_categoriables')->where('sub_categoriable_id', $application->id)->whereNotIn('sub_category_id', $request->post('confirmed-sub-categories'))->delete();
            DB::table('ind_work_method')->where('ind_id', $application->id)->whereNotIn('sub_category_id', $request->post('confirmed-sub-categories'))->delete();
        }

        if ($subCategoryIds) {
            // Delete sub-categories
            foreach ($subCategories as $subCategory) {
                if (!in_array($subCategory->id, $subCategoryIds)) {
                    $application->subCategories()->detach($subCategory->id);
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
            $application->thana_id = $thana->id;
        }

        if ($union->is_pending == 1) {
            $union->bn_name = $request->post('union-request');
            $union->thana_id = $thana->id;
            $union->is_pending = 0;
            $union->save();
        } else {
            $application->union_id = $union->id;
        }

        if ($village->is_pending == 1) {
            $village->bn_name = $request->post('village-request');
            $village->union_id = $union->id;
            $village->is_pending = 0;
            $village->save();
        } else {
            $application->village_id = $village->id;
        }

        $duration = Package::with('properties')->find($request->post('package'))->properties->groupBy('name')['duration'][0]->value;

        $application->expire = Carbon::now()->addDays($duration)->format('Y-m-d H:i:s');
        $application->save();

        if ($request->has('category-request') && $request->filled('category')) {
            $application->category()->delete();
        }

        DB::commit();

        return redirect(route('backend.request.ind-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy(Ind $application)
    {
        DB::beginTransaction();

        $application->load([
            'category',
            'thana',
            'union',
            'village',
            'subCategories' => function ($query) {
                $query->onlyPending();
            }
        ]);

        $category = $application->category;
        $thana = $application->thana;
        $union = $application->union;
        $village = $application->village;
        $subCategories = $application->subCategories;

        $application->subCategories()->detach();
        SubCategory::whereIn('id', $subCategories->pluck('id')->toArray())->delete();

        $application->forceDelete();
        $category->is_confirmed == 0 && $category->delete();
        $village->is_pending == 1 && $village->delete();
        $union->is_pending == 1 && $union->delete();
        $thana->is_pending == 1 && $thana->delete();

        // TODO:: Don't forget to delete documents/images

        DB::commit();

        return redirect(route('backend.request.ind-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
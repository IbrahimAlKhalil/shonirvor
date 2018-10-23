<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Village;
use App\Models\WorkMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = Ind::onlyPending()->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show(Ind $serviceRequest)
    {
        $serviceRequest->load([
            'referredBy.user',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'workMethods',
            'subCategories'
        ]);

        $workMethods = WorkMethod::all();
        $indWorkMethods = $serviceRequest->workMethods->groupBy('pivot.sub_category_id');
        $navs = $this->navs();
        $thanas = $serviceRequest->thana->is_pending ? Thana::where('district_id', $serviceRequest->district_id)->get() : [];
        $unions = !$serviceRequest->thana->is_pending && $serviceRequest->union->is_pending ? Union::where('thana_id', $serviceRequest->thana_id)->get() : [];
        $villages = !$serviceRequest->union->is_pending && $serviceRequest->village->is_pending ? Village::where('union_id', $serviceRequest->union_id)->get() : [];
        $categories = !$serviceRequest->category->is_confirmed ? Category::onlyInd()->onlyConfirmed()->get() : [];
        return view('backend.ind-service-request.show', compact('serviceRequest', 'navs', 'thanas', 'unions', 'villages', 'categories', 'workMethods', 'indWorkMethods'));
    }

    public function update(Request $request, Ind $serviceRequest)
    {
        // TODO:: Make a request class

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
        $subCategoryIds = array_map(function ($element) {
            return $element['id'];
        }, $request->post('sub-categories'));
        $thana = !isset($thana) ? $serviceRequest->thana : $thana;
        $union = !isset($union) ? $serviceRequest->union : $union;
        $village = !isset($village) ? $serviceRequest->village : $village;

        // Update category
        if ($category->is_confirmed == 0) {
            $category->update(['name' => $request->post('category-request'), 'is_confirmed' => 1]);
        } else {
            $serviceRequest->category_id = $category->id;
        }

        if ($request->has('sub-categories')) {
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

        $serviceRequest->is_pending = 0;
        $serviceRequest->save();

        if ($request->has('category-request') && $request->filled('category')) {
            $serviceRequest->category()->delete();
        }

        DB::commit();

        return redirect(route('individual-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy(Ind $serviceRequest)
    {
        DB::beginTransaction();

        $serviceRequest->load([
            'category',
            'thana',
            'union',
            'village',
            'subCategories' => function ($query) {
                $query->onlyPending();
            }
        ]);

        $category = $serviceRequest->category;
        $thana = $serviceRequest->thana;
        $union = $serviceRequest->union;
        $village = $serviceRequest->village;
        $subCategories = $serviceRequest->subCategories;

        $serviceRequest->subCategories()->detach();
        SubCategory::whereIn('id', $subCategories->pluck('id')->toArray())->delete();

        $serviceRequest->forceDelete();
        $category->is_confirmed == 0 && $category->delete();
        $village->is_pending == 1 && $village->delete();
        $union->is_pending == 1 && $union->delete();
        $thana->is_pending == 1 && $thana->delete();

        // TODO:: Don't forget to delete documents/images

        DB::commit();

        return redirect(route('individual-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('individual-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-edit.index'), 'text' => 'প্রোফাইল এডিট রিকোয়েস্ট']
        ];
    }
}
<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = Org::getOnly('pending')->paginate(15);
        $navs = $this->navs();
        return view('backend.org-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show(Org $serviceRequest)
    {
        $navs = $this->navs();
        return view('backend.org-service-request.show', compact('serviceRequest', 'navs'));
    }

    public function store(Request $request)
    {
        // TODO:: Make a request class

        DB::beginTransaction();

        $org = Org::find($request->post('id'));
        $category = Category::find($org->category->id);
        $thana = Thana::find($org->thana->id);
        $union = Union::find($org->union->id);

        if ($category->is_confirmed == 0) {
            $category->name = $request->post('category');
            $category->is_confirmed = 1;
            $category->save();
        }

        if ($thana->is_pending == 1) {
            $thana->bn_name = $request->post('thana');
            $thana->is_pending = 0;
            $thana->save();
        }

        if ($union->is_pending == 1) {
            $union->bn_name = $request->post('union');
            $union->is_pending = 0;
            $union->save();
        }


        $previousRequested = $org->subCategories('requested');
        $previousRequested->detach();
        $previousRequested->delete();

        if ($request->has('sub-categories')) {
            $data = [];
            foreach ($request->post('sub-categories') as $subCategoryName) {
                // TODO:: Please check null in the request file, not here!
                !is_null($subCategoryName) && array_push($data, [
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            $requestedSubCategories = $category->subCategories()->createMany($data);
            // associate sub-categories
            $org->subCategories()->saveMany($requestedSubCategories);
        }

        $org->is_pending = 0;
        $org->save();

        DB::commit();

        return redirect(route('organization-service-request.index'))->with('success', 'Service Provider approved successfully!');
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

        return redirect(route('organization-service-request.index'))->with('success', 'Service Provider request rejected successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('organization-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
        ];
    }
}

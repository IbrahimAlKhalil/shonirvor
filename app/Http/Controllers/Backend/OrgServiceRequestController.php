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
        $serviceRequests = Org::onlyPending()->orderBy('updated_at', 'DSC')->paginate(15);
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
            $category->update(['name' => $request->post('category'), 'is_confirmed' => 1]);
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

        if ($request->has('sub-categories')) {
            foreach ($request->post('sub-categories') as $subCategory) {
                $org->subCategories()->find($subCategory['id'])->update(['name' => $subCategory['name'], 'is_confirmed' => 1]);
            }
        }

        $org->is_pending = 0;
        $org->save();

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

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = Ind::getOnly('pending')->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show(Ind $serviceRequest)
    {
        $navs = $this->navs();
        return view('backend.ind-service-request.show', compact('serviceRequest', 'navs'));
    }

    public function store(Request $request)
    {
        // TODO:: Make a request class

        DB::beginTransaction();

        $ind = Ind::find($request->post('id'));
        $category = Category::find($ind->category->id);
        $thana = Thana::find($ind->thana->id);
        $union = Union::find($ind->union->id);

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
                $ind->subCategories()->find($subCategory['id'])->update(['name' => $subCategory['name'], 'is_confirmed' => 1]);
            }
        }

        $ind->is_pending = 0;
        $ind->save();

        DB::commit();

        return redirect(route('individual-service-request.index'))->with('success', 'অনুরোধটি সফলভাবে গৃহীত হয়েছে!');
    }

    public function destroy(Ind $serviceRequest)
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
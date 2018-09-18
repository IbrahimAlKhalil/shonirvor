<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        $categories = Category::getAll('ind')->get();
        $subCategories = SubCategory::getAll('ind')->get();
        $navs = $this->navs();
        return view('backend.ind-service-request.show', compact('serviceRequest', 'navs', 'categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        // TODO:: Make a request class

        DB::beginTransaction();

        $ind = Ind::find($request->post('id'));
        $category = Category::find($ind->category->id);

        if ($category->is_confirmed == 0) {
            $category->name = $request->post('category');
            $category->is_confirmed = 1;
            $category->save();
        }


        $previousRequested = $ind->subCategories('requested');
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
            $ind->subCategories()->saveMany($requestedSubCategories);
        }

        $ind->is_pending = 0;
        $ind->save();

        DB::commit();

        return redirect(route('individual-service-request.index'))->with('success', 'Service Provider approved successfully!');
    }

    public function destroy(Ind $serviceRequest)
    {
        $category = $serviceRequest->category;
        $subCategories = $serviceRequest->subCategories('requested');

        $serviceRequest->subCategories()->detach();
        $subCategories->delete();

        if ($category->is_confirmed == 0) {
            $category->delete();
        }

        // TODO:: Don't forget to delete documents/images

        return redirect(route('individual-service-request.index'))->with('success', 'Service Provider request rejected successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('individual-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
        ];
    }
}
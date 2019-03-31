<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\StoreSubCategory;

// TODO:: Request validation classes are empty.

class IndSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function store(StoreSubCategory $request)
    {
        $subCategory = new SubCategory();
        $subCategory->name = $request->input('name');
        $subCategory->category_id = $request->input('category-id');
        $subCategory->save();

        return back()->with('success', $request->input('name').' সাব-ক্যাটাগরিটি যুক্ত হয়েছে!');
    }

    public function update(UpdateCategory $request, SubCategory $subCategory)
    {
        $oldName = $subCategory->name;

        $subCategory->name = $request->input('name');
        $subCategory->save();

        return back()->with('success', $oldName.' সাব-ক্যাটাগরিটি এডিট হয়েছে।');
    }

    public function destroy(SubCategory $subCategory)
    {

        $subCategory->inds()->detach();

        $subCategory->delete();

        return back()->with('success', $subCategory->name.' সাব-ক্যাটাগরিটি ডিলিট হয়েছে।');
    }
}

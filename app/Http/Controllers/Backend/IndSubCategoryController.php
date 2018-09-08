<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditIndSubCategory;
use App\Http\Requests\StoreIndSubCategory;
use App\Models\IndSubCategory;
use Illuminate\Http\Request;

class IndSubCategoryController extends Controller
{


    public function store(StoreIndSubCategory $request)
    {
        $indSubCategory = new IndSubCategory;
        $indSubCategory->ind_category_id = $request->post('category-id');
        $indSubCategory->category = $request->post('sub-category');
        $indSubCategory->save();

        return back()->with('success', 'Sub-category "' . $request->post('category') . '" Added Successfully!');
    }


    public function update(StoreEditIndSubCategory $request, IndSubCategory $indSubCategory)
    {
        $indSubCategory->category = $request->post('edit-sub-category');
        $indSubCategory->save();
        return back()->with('success', 'Sub-Category Renamed Successfully!');
    }


    public function destroy(IndSubCategory $indSubCategory)
    {
        $indSubCategory->delete();

        return back()->with('success', 'Sub-category Deleted Successfully!');
    }
}

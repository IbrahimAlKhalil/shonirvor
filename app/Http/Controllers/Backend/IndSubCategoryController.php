<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\StoreSubCategory;

// TODO:: This requests class is empty, don't forget about that.

class IndSubCategoryController extends Controller
{


    public function store(StoreSubCategory $request)
    {
        Category::find($request->post('category-id'))->subCategories()->create([
            'name' => $request->post('sub-category')
        ]);

        return back()->with('success', 'Sub-category "' . $request->post('category') . '" Added Successfully!');
    }


    public function update(UpdateCategory $request, SubCategory $subCategory)
    {
        $subCategory->update([
            'name' => $request->post('edit-sub-category')
        ]);
        return back()->with('success', 'Sub-Category Renamed Successfully!');
    }


    public function destroy(SubCategory $subCategory)
    {
        // TODO:: There can be some kind of gotcha about 'Cascade' deleting or updating, I'm not sure!.

        $subCategory->delete();

        return back()->with('success', 'Sub-category Deleted Successfully!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategory;
use App\Http\Requests\StoreSubCategory;

// TODO:: This requests class is empty, don't forget about that.

class IndSubCategoryController extends Controller
{


    public function store(StoreSubCategory $request)
    {
        DB::beginTransaction();

        Category::find($request->post('category-id'))->subCategories()->create([
            'name' => $request->post('sub-category')
        ]);

        DB::commit();
        return back()->with('success', 'Sub-category "' . $request->post('category') . '" Added Successfully!');
    }


    public function update(UpdateCategory $request, SubCategory $subCategory)
    {
        DB::beginTransaction();

        $subCategory->update([
            'name' => $request->post('edit-sub-category')
        ]);

        DB::commit();

        return back()->with('success', 'Sub-Category Renamed Successfully!');
    }


    public function destroy(SubCategory $subCategory)
    {
        DB::beginTransaction();
        // TODO:: There can be some kind of gotcha about 'Cascade' deleting or updating, I'm not sure!.

        $subCategory->delete();
        DB::commit();

        return back()->with('success', 'Sub-category Deleted Successfully!');
    }
}

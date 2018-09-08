<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditOrgSubCategory;
use App\Http\Requests\StoreOrgSubCategory;
use App\Models\OrgSubCategory;
use Illuminate\Http\Request;

class OrgSubCategoryController extends Controller
{


    public function store(StoreOrgSubCategory $request)
    {
        $orgSubCategory = new OrgSubCategory();
        $orgSubCategory->org_category_id = $request->post('category-id');
        $orgSubCategory->category = $request->post('sub-category');
        $orgSubCategory->save();

        return back()->with('success', 'Sub-category "' . $request->post('sub-category') . '" Added Successfully!');
    }


    public function update(StoreEditOrgSubCategory $request, OrgSubCategory $orgSubCategory)
    {
        $orgSubCategory->category = $request->post('edit-sub-category');
        $orgSubCategory->save();
        return back()->with('success', 'Sub-Category Renamed Successfully!');
    }


    public function destroy(OrgSubCategory $orgSubCategory)
    {
        //
    }
}

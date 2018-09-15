<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrgCategory;
use App\Models\OrgCategory;
use Illuminate\Http\Request;

class OrgCategoryController extends Controller
{

    public function index()
    {
        $orgCategories = OrgCategory::orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();

        return view('backend.org-category.index', compact('orgCategories', 'navs'));
    }


    public function create()
    {
        //
    }


    public function store(StoreOrgCategory $request)
    {
        $orgSubCategory = new OrgCategory;
        $orgSubCategory->category = $request->post('category');
        $orgSubCategory->save();

        return redirect(route('organization-category.show', $orgSubCategory->id))->with('success', 'Category "' . $request->post('category') . '" Added Successfully!');
    }


    public function show(OrgCategory $orgCategory)
    {
        $navs = $this->navs();
        $orgSubCategories = $orgCategory->subCategories()->orderBy('updated_at', 'DSC')->paginate(10);
        return view('backend.org-category.show', compact('orgCategory', 'orgSubCategories', 'navs'));
    }


    public function edit(OrgCategory $orgCategory)
    {
        //
    }


    public function update(StoreOrgCategory $request, OrgCategory $orgCategory)
    {
        $orgCategory->category = $request->post('category');
        $orgCategory->save();
        return back()->with('success', 'Category Renamed Successfully!');
    }


    public function destroy(OrgCategory $orgCategory)
    {
        $orgCategory->delete();
        return redirect(route('organization-category.index'))->with('success', 'Category Deleted Successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-category.index'), 'text' => 'Organization Categories']
        ];
    }
}

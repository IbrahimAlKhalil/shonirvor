<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndCategory;
use App\Models\IndCategory;
use Illuminate\Http\Request;

class IndCategoryController extends Controller
{

    public function index()
    {
        $indCategories = IndCategory::orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();

        return view('backend.ind-category.index', compact('indCategories', 'navs'));
    }


    public function create()
    {
        return view('backend.ind-category.create');
    }


    public function store(StoreIndCategory $request)
    {
        $indCategory = new IndCategory;
        $indCategory->category = $request->post('category');
        $indCategory->save();

        return redirect(route('individual-category.show', $indCategory->id))->with('success', 'Category "' . $request->post('category') . '" Added Successfully!');
    }


    public function show(IndCategory $indCategory)
    {
        $navs = $this->navs();
        $indSubCategories = $indCategory->subCategories()->orderBy('updated_at', 'DSC')->paginate(10);
        return view('backend.ind-category.show', compact('indCategory', 'indSubCategories', 'navs'));
    }


    public function edit(IndCategory $indCategory)
    {
        //
    }


    public function update(StoreIndCategory $request, IndCategory $indCategory)
    {
        $indCategory->category = $request->post('category');
        $indCategory->save();
        return back()->with('success', 'Category Renamed Successfully!');
    }

    public function destroy(IndCategory $indCategory)
    {
        $indCategory->delete();
        return redirect(route('individual-category.index'))->with('success', 'Category Deleted Successfully!');
    }

    private function navs()
    {
        return [
            ['route' => 'individual-category.index', 'text' => 'Individual Categories']
        ];
    }
}

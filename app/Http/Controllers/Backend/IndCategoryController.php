<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\ServiceType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;

// TODO:: Some of these requests classes are empty, so fill these with whatever you can.

class IndCategoryController extends Controller
{

    public function index()
    {
        $categories = Category::getAll('ind')->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();

        return view('backend.ind-category.index', compact('categories', 'navs'));
    }

    public function store(StoreCategory $request)
    {
        $category = new Category([
            'name' => $request->post('category'),
            'is_confirmed' => 1,
            'image' => $request->file('image')->store('category-images')
        ]);
        ServiceType::where('name', 'ind')->first()->categories()->save($category);

        return redirect(route('individual-category.show', $category->id))->with('success', 'Category "' . $request->post('category') . '" Added Successfully!');
    }


    public function show(Category $category)
    {
        $navs = $this->navs();
        $subCategories = $category->subCategories('confirmed')->orderBy('updated_at', 'DSC')->paginate(10);
        return view('backend.ind-category.show', compact('category', 'subCategories', 'navs'));
    }


    public function update(UpdateCategory $request, Category $category)
    {
        $category->update([
            'name' => $request->post('category')
        ]);
        return back()->with('success', 'Category Renamed Successfully!');
    }

    public function destroy(Category $category)
    {
        // TODO:: There can be some kind of gotcha about 'Cascade' deleting or updating, I'm not sure!.
        // TODO:: Delete image
        $category->delete();
        return redirect(route('individual-category.index'))->with('success', 'Category Deleted Successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-category.index'), 'text' => 'ব্যক্তি সেবা প্রদানকারী বিভাগসমূহ']
        ];
    }
}

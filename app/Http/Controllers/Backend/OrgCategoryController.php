<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\ServiceType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;
use Illuminate\Support\Facades\Storage;

// TODO:: Need to do request validation.

class OrgCategoryController extends Controller
{

    public function index()
    {
        $categories = Category::getAll('org')
            ->orderBy('updated_at', 'DSC')
            ->paginate(15);

        $navs = $this->navs();

        return view('backend.org-category.index', compact('categories', 'navs'));
    }


    public function store(StoreCategory $request)
    {
        $category = new Category();
        $category->name = $request->input('category');
        $category->service_type_id = 2;
        $category->image = $request->file('image')->store('category-images');
        $category->save();

        return back()->with('success', $request->input('category').' ক্যাটাগরিটি তৈরি হয়েছে।');
    }


    public function show(Category $category)
    {
        $subCategories = $category->subCategories('confirmed')
            ->orderBy('updated_at', 'DSC')
            ->paginate(10);

        $navs = $this->navs();

        return view('backend.org-category.show', compact('category', 'subCategories', 'navs'));
    }


    public function update(UpdateCategory $request, Category $category)
    {
        $oldName = $category->name;

        $category->name = $request->input('name');
        if ($request->hasFile('image')) {
            Storage::delete($category->image);
            $category->image = $request->file('image')->store('category-images');
        }
        $category->save();

        return back()->with('success', $oldName.' ক্যাটাগরিটি এডিট হয়েছে।');
    }

    public function destroy(Category $category)
    {
        if ($category->subCategories->isNotEmpty()) {
            return back()->with('error', $category->name.' ক্যাটাগরিটির ভিতর সাব-ক্যাটাগরি রয়েছে তাই এটি ডিলিট হবে না।');
        }
        else
        {
            $category->delete();
            Storage::delete($category->image);

            return back()->with('success', $category->name.' ক্যাটাগরিটি ডিলিট হয়েছে।');
        }
    }

    private function navs()
    {
        return [
            ['url' => route('individual-category.index'), 'text' => 'বেক্তিগত ক্যাটাগরি সমূহ'],
            ['url' => route('organization-category.index'), 'text' => 'প্রাতিষ্ঠানিক ক্যাটাগরি সমূহ']
        ];
    }
}

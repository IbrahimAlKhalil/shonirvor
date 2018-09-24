<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\ServiceType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategory;
use App\Http\Requests\UpdateCategory;

// TODO:: Some of these requests classes are empty, so fill these with whatever you can.

class OrgCategoryController extends Controller
{

    public function index()
    {
        $categories = Category::getAll('org')->orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();

        return view('backend.org-category.index', compact('categories', 'navs'));
    }


    public function store(StoreCategory $request)
    {
        DB::beginTransaction();

        $category = new Category([
            'name' => $request->post('category'),
            'is_confirmed' => 1
        ]);
        ServiceType::getThe('org')->categories()->save($category);

        DB::commit();
        return redirect(route('organization-category.show', $category->id))->with('success', 'Category "' . $request->post('category') . '" সফলভাবে যুক্ত হয়েছে!');
    }


    public function show(Category $category)
    {
        $navs = $this->navs();
        $subCategories = $category->subCategories('confirmed')->orderBy('updated_at', 'DSC')->paginate(10);
        return view('backend.org-category.show', compact('category', 'subCategories', 'navs'));
    }


    public function update(UpdateCategory $request, Category $category)
    {
        DB::beginTransaction();
        $category->update([
            'name' => $request->post('category')
        ]);
        DB::commit();
        return back()->with('success', 'Category Renamed Successfully!');
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        // TODO:: There can be some kind of gotcha about 'Cascade' deleting or updating, I'm not sure!.

        $category->delete();

        DB::commit();
        return redirect(route('organization-category.index'))->with('success', 'ক্যাটাগরিটি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-category.index'), 'text' => 'Organization Categories']
        ];
    }
}

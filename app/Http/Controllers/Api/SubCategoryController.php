<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    public function subCategories(Request $request)
    {
        $result = null;
        $id = theId();

        if ($id) {
            return SubCategory::find($id);
        }

        $hasServiceType = $request->has('service');
        $hasCategory = $request->has('category');
        $serviceType = $request->get('service');
        $category = $request->get('category');

        if ($hasServiceType) {
            switch ($hasCategory) {
                case true:
                    $result = Category::find($category)->subCategories()->where('is_confirmed', 1)->get();
                    break;
                case false:
                    $result = SubCategory::getAll($serviceType)->get();
            }
            return $result;
        }

        if ($hasCategory) {
            return Category::find($category)->subCategories()->where('is_confirmed', 1)->get();
        }

        return SubCategory::where('is_confirmed', 1)->get();
    }
}
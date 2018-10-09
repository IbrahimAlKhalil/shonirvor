<?php

namespace App\Http\Controllers\Api;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = [];

        if ($request->filled('sub-category')) {
            $keyword = $request->get('sub-category');
            $data = SubCategory::join('categories', 'sub_categories.category_id', 'categories.id')
                ->select(
                    'sub_categories.id',
                    'sub_categories.name',
                    'categories.name as category_name'
                )
                ->where('sub_categories.name', 'like', '%'.$keyword.'%')
                ->onlyConfirmed()
                ->get();
        }

        return response()->json($data);
    }
}

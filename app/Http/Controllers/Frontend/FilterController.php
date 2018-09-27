<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Ind;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Sandofvega\Bdgeocode\Models\Division;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        dd('pause');
        $divisions = Division::select('id', 'bn_name')->get();
        $ads = Ad::all();
        if ($ads->count() >= 3) {
            $ads = $ads->random(3);
        }

        if ($request->filled('sub-category')) {
            $subCategory = SubCategory::find($request->get('sub-category'));

            if ($subCategory->category->type->name === 'ind') {
                $providers = $subCategory->inds;
            }
            elseif ($subCategory->category->type->name === 'org') {
                $providers = $subCategory->inds;
            }
            dd($providers);
        } elseif ($request->filled('category')) {
            $category = Category::find($request->filled('category'));
        }

        if (!isset($providers)) {
            $providers = Ind::all();
        }

        return view('frontend.filter', compact('divisions', 'ads', 'providers'));
    }
}

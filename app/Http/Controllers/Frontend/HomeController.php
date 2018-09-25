<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Ind;
use App\Models\Org;
use Sandofvega\Bdgeocode\Models\Division;

class HomeController extends Controller
{
    public function index()
    {
        $indCategories = Category::where('service_type_id', 1)->get();
        $orgCategories = Category::where('service_type_id', 2)->get();

        $indServices = Ind::take(5)->get();
        $orgServices = Org::take(5)->get();

        $divisions = Division::select('id', 'bn_name')->get();

        return view('frontend.home', compact('divisions', 'indCategories', 'orgCategories', 'indServices', 'orgServices'));
    }
}

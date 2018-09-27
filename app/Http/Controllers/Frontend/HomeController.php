<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Ind;
use App\Models\Org;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Sandofvega\Bdgeocode\Models\Division;

class HomeController extends Controller
{
    public function index()
    {
        $divisions = Division::select('id', 'bn_name')->get();

        $indCategories = Category::where('service_type_id', 1)->get();
        $orgCategories = Category::where('service_type_id', 2)->get();

        $indServices = Ind::all()->random(10);
        $orgServices = Org::all()->random(10);

        $ads = Ad::all();
        if ($ads->count() >= 3) {
            $ads = $ads->random(3);
        }

        return view('frontend.home', compact('divisions', 'ads', 'indCategories', 'orgCategories', 'indServices', 'orgServices'));
    }
}
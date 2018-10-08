<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\Org;
use App\Models\Category;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {
        $indCategories = Category::withCount(['indServices' => function ($query) {
                $query->where('is_pending', 0);
            }])
            ->where('is_confirmed', 1)
            ->where('service_type_id', 1)
            ->orderBy('ind_services_count', 'desc')
            ->take(5)
            ->get();

        $orgCategories = Category::withCount(['orgServices' => function ($query) {
                $query->where('is_pending', 0);
            }])
            ->where('is_confirmed', 1)
            ->where('service_type_id', 2)
            ->orderBy('org_services_count', 'desc')
            ->take(5)
            ->get();

        $indServices = Ind::onlyTop()
            ->select('inds.*')
            ->withFeedbacksAvg()
            ->inRandomOrder()
            ->get();

        $orgServices = Org::onlyTop()
            ->select('orgs.*')
            ->withFeedbacksAvg()
            ->inRandomOrder()
            ->get();

        return view('frontend.home', compact('indCategories', 'orgCategories', 'indServices', 'orgServices'));
    }
}
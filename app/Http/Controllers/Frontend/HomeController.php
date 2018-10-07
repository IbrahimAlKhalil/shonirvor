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
        $indCategories = Category::onlyInd()
            ->select('id', 'name', 'image')
            ->withCount(['indServices' => function ($query) {
                $query->onlyApproved();
            }])
            ->onlyConfirmed()
            ->orderBy('ind_services_count', 'desc')
            ->take(10)
            ->get();

        $orgCategories = Category::onlyOrg()
            ->select('id', 'name', 'image')
            ->withCount(['orgServices' => function ($query) {
                $query->onlyApproved();
            }])
            ->onlyConfirmed()
            ->orderBy('org_services_count', 'desc')
            ->take(10)
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
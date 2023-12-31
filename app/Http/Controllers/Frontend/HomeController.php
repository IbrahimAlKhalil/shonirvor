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
            ->take(9)
            ->get();

        $orgCategories = Category::onlyOrg()
            ->select('id', 'name', 'image')
            ->withCount(['orgServices' => function ($query) {
                $query->onlyApproved();
            }])
            ->onlyConfirmed()
            ->orderBy('org_services_count', 'desc')
            ->take(9)
            ->get();

        $indTopServices = Ind::onlyTop()
            ->onlyApproved()
            ->select('inds.id', 'mobile', 'inds.user_id', 'inds.status', 'inds.is_available', 'category_id', 'district_id', 'thana_id', 'union_id')
            ->with(['user:id,name,photo', 'category:id,name', 'district:id,bn_name as name', 'thana:id,bn_name as name', 'union:id,bn_name as name', 'slug'])
            ->withFeedbacksAvg()
            ->inRandomOrder()
            ->take(10)
            ->get();

        $orgTopServices = Org::onlyTop()
            ->onlyApproved()
            ->select('orgs.id', 'name', 'mobile', 'logo', 'orgs.user_id', 'category_id', 'district_id', 'thana_id', 'union_id')
            ->with(['category:id,name', 'district:id,bn_name as name', 'thana:id,bn_name as name', 'union:id,bn_name as name', 'slug'])
            ->withFeedbacksAvg()
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('frontend.home', compact('indCategories', 'orgCategories', 'indTopServices', 'orgTopServices'));
    }
}
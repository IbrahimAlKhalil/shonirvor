<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Ind;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\WorkMethod;
use Illuminate\Support\Facades\Auth;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndMyServiceController extends Controller
{
    public function show(Ind $service)
    {
        $service->load([
            'referredBy.user',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategories',
            'workMethods'
        ]);

        $navs = $this->navs();
        $workMethods = WorkMethod::all();
        $indWorkMethods = $service->workMethods->groupBy('pivot.sub_category_id');

        return view('frontend.my-services.ind-service', compact('service', 'navs', 'workMethods', 'indWorkMethods'));
    }

    private function navs()
    {
        $navs = [];

        $inds = Auth::user()
            ->inds()
            ->with('category')
            ->withTrashed()
            ->get();

        $orgs = Auth::user()
            ->orgs()
            ->with('category')
            ->withTrashed()
            ->get();

        foreach ($inds as $ind) {
            array_push($navs, [
                'url' => route('frontend.my-service.ind.show', $ind->id),
                'text' => $ind->category->name
            ]);
        }

        foreach ($orgs as $org) {
            array_push($navs, [
                'url' => route('frontend.my-service.org.show', $org->id),
                'text' => $org->name
            ]);
        }

        return $navs;
    }
}

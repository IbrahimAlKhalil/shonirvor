<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Http\Controllers\Controller;
use App\Models\Village;
use App\Models\WorkMethod;
use Illuminate\Support\Facades\Auth;
use Sandofvega\Bdgeocode\Models\Division;

class IndMyServiceController extends Controller
{
    public function show()
    {
        $service = Ind::with('district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods')
            ->withTrashed()->find(request()->service);

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

    public function edit($id)
    {
        $service = Ind::with('district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods')
            ->withTrashed()->find($id);

        $navs = $this->navs();
        $workMethods = WorkMethod::all();
        $indWorkMethods = $service->workMethods->groupBy('pivot.sub_category_id');

        $divisions = Division::all();
        $districts = $service->division->districts;
        $thanas = $service->district->thanas;
        $unions = $service->thana->unions;
        $villages = Village::where('union_id', $service->union_id)->get();

        return view('frontend.my-services.ind-service-edit', compact('service', 'navs', 'workMethods', 'indWorkMethods', 'divisions', 'districts', 'thanas', 'villages', 'unions'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Models\WorkMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrgMyServiceController extends Controller
{
    public function show(Org $service)
    {
        $service->load([
            'referredBy.user',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategories'
        ]);

        $navs = $this->navs();

        return view('frontend.my-services.org-service', compact('service', 'navs'));
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

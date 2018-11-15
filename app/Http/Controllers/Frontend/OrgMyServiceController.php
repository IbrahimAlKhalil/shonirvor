<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\UpdateOrgMyService;
use App\Models\Division;
use App\Models\Org;
use App\Models\ServiceEdit;
use App\Models\Village;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrgMyServiceController extends Controller
{
    public function show()
    {
        $service = Org::with('district', 'thana', 'union', 'village', 'category', 'subCategories')
            ->withTrashed()->find(request()->service);

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

    public function edit($id)
    {
        $service = Org::with('district', 'thana', 'union', 'village', 'category', 'subCategories', 'additionalPrices')
            ->onlyApproved()->findOrFail($id);

        $navs = $this->navs();

        $divisions = Division::all();
        $districts = $service->division->districts;
        $thanas = $service->district->thanas;
        $unions = $service->thana->unions;
        $villages = Village::where('union_id', $service->union_id)->get();

        return view('frontend.my-services.org-service-edit', compact('service', 'navs', 'divisions', 'districts', 'thanas', 'villages', 'unions'));
    }

    public function update(Org $service, UpdateOrgMyService $request)
    {
        $data = $request->only([
            'mobile',
            'email',
            'website',
            'facebook',
            'day',
            'month',
            'year',
            'qualification',
            'nid',
            'division',
            'district',
            'thana',
            'union',
            'village',
            'address',
            'sub-categories',
            'sub-category-requests',
            'kaj',
            'kaj-requests'
        ]);

        if ($request->hasFile('cover-photo')) {
            $data['cover-photo'] = $request->file('cover-photo')->store('org/' . $service->id . '/' . 'docs');
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('org/' . $service->id);
        }

        if ($request->has('work-images')) {
            $images = [];

            // TODO:: Validation
            foreach ($request->post('work-images') as $id => $image) {
                if (isset($image['description']) && !is_null($image['description'])) {
                    $images[$id]['description'] = $image['description'];
                };
            }

            if ($request->hasFile('work-images')) {
                foreach ($request->file('work-images') as $id => $image) {
                    if (isset($image['file']) && !is_null($image['file'])) {
                        $images[$id]['file'] = $image['file']->store('ind/' . $service->id . '/' . 'images');
                    };
                }
            }

            $data['images'] = $images;
        }

        DB::beginTransaction();

        $edit = new ServiceEdit;
        $edit->service_editable_id = $service->id;
        $edit->service_editable_type = 'org';
        $edit->data = $data;
        $edit->save();
        DB::commit();

        return redirect(route('frontend.my-service.org.show', $service->id))->with('success', 'আপনার আবেদনটি জমা হয়েছে। শীঘ্রয় এডমিন, আবেদনটি রিভিউ করবেন।');
    }
}

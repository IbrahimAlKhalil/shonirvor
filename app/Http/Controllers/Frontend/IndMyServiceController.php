<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\UpdateIndMyService;
use App\Models\Ind;
use App\Models\ServiceEdit;
use App\Models\Village;
use App\Models\Division;
use App\Models\WorkMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndMyServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('provider');
    }

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

        $services = Auth::user()
            ->inds()
            ->with('category')
            ->withTrashed()
            ->get();

        $orgs = Auth::user()
            ->orgs()
            ->with('category')
            ->withTrashed()
            ->get();

        foreach ($services as $service) {
            array_push($navs, [
                'url' => route('frontend.my-service.ind.show', $service->id),
                'text' => $service->category->name
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
            ->onlyApproved()->find($id);

        if (!$service) {
            abort(404, 'Service is pending or service not found');
        }

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

    public function update(Ind $service, UpdateIndMyService $request)
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
            'sub-category-requests'
        ]);

        if ($request->hasFile('cv')) {
            $data['cv'] = $request->file('cv')->store('ind/' . $service->id . '/' . 'docs');
        }

        if ($request->hasFile('experience-certificate')) {
            $data['experience-certificate'] = $request->file('cv')->store('ind/' . $service->id . '/' . 'docs');
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

        // identities
        if ($request->hasFile('identities')) {
            $data['identities'] = [];
            foreach ($request->file('identities') as $identity) {
                array_push($data['identities'], $identity->store('user-photos/' . $service->user->id));
            }
        }

        DB::beginTransaction();

        $edit = new ServiceEdit;
        $edit->service_editable_id = $service->id;
        $edit->service_editable_type = 'ind';
        $edit->data = $data;
        $edit->save();
        DB::commit();

        return redirect(route('frontend.my-service.ind.show', $service->id))->with('success', 'আপনার আবেদনটি জমা হয়েছে। শীঘ্রয় এডমিন, আবেদনটি রিভিউ করবেন।');
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\UpdateIndMyService;
use App\Models\Ind;
use App\Models\ServiceEdit;
use App\Models\Village;
use App\Models\Division;
use App\Models\WorkMethod;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndMyServiceController extends Controller
{
    private $editExists;

    public function __construct()
    {
        $this->middleware('provider');
        $this->editExists = ServiceEdit::where('service_editable_type', 'ind')->where('service_editable_id', request('service'))->exists();
    }

    public function show($id)
    {
        $service = Ind::with('district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods', 'slug')
            ->withTrashed()->where('user_id', Auth::id())->findOrFail($id);

        $navs = $this->navs();
        $workMethods = WorkMethod::all();
        $indWorkMethods = $service->workMethods->groupBy('pivot.sub_category_id');
        $editExists = $this->editExists;

        return view('frontend.my-services.ind-service', compact('service', 'navs', 'workMethods', 'indWorkMethods', 'editExists'));
    }

    public function edit($id)
    {
        if ($this->editExists) {
            abort(404, 'You have already an edit request pending');
        }

        $service = Ind::with('division', 'district', 'thana', 'union', 'village', 'category', 'subCategories', 'workMethods', 'slug')
            ->onlyApproved()->where('user_id', Auth::id())->findOrFail($id);

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

    public function update(UpdateIndMyService $request, $id)
    {
        if ($this->editExists) {
            abort(404, 'You have already an edit request pending');
        }

        $service = Ind::onlyApproved()->findOrFail($id);

        $data = $request->only([
            'description',
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
            'slug',
            'address',
            'sub-categories',
            'sub-category-requests'
        ]);

        if ($request->hasFile('cover-photo')) {
            $data['cover-photo'] = $request->file('cover-photo')->store('ind/' . $service->id . '/' . 'docs');
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

        $data['new-work-images'] = [];
        if ($request->file('new-work-images')) {
            foreach ($request->file('new-work-images') as $image) {
                array_push($data['new-work-images'], [
                    'file' => $image['file']->store('ind/' . $service->id . '/' . 'images')
                ]);
            }
            $count = 0;
            foreach ($request->post('new-work-images') as $item) {
                if (isset($data['new-work-images'][$count])) {
                    $data['new-work-images'][$count]['description'] = $item['description'];
                }
                $count++;
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

    public function destroy(Ind $service)
    {
        DB::beginTransaction();
        // TODO: Delete files
        // TODO: Delete pending things such as edit request, renew request etc.
        $service->forceDelete();

        deleteIndDocs($service);

        DB::commit();

        return redirect(route('profile.index'))->with('success', 'একাউন্টটি সফলভাবে মুছে ফেলা হয়েছে');
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
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use App\Models\Category;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Division;
use App\Models\Union;
use App\Models\Thana;
use App\Models\District;

class OrgProfileController extends Controller
{
    public function edit($id)
    {
        $provider = Org::with(['division', 'district', 'thana', 'union'])->find($id);
        // TODO:: Move this validation to requests class
        if ($provider->user_id != Auth::id()) {
            return redirect(route('organization-service-registration.index'));
        }

        if ($provider->edit()->exists()) {
            return back()->with('error', 'ইতোমধ্যেই আপনার প্রোফাইলের একটি সম্পাদনা প্রক্রিয়াধীন আছে');
        }

        $categories = Category::getAll('org')->get();
        // TODO:: Don't pass all the subcategories, districts, thanas, unions after implementing ajax
        $subCategories = SubCategory::onlyOrg()->get();
        $divisions = Division::all();
        $districts = $provider->division()->with('districts')->first()->districts;
        $thanas = $provider->district->thanas()->where('is_pending', 0)->get();
        $unions = $provider->thana->unions()->where('is_pending', 0)->get();

        $isPicExists = $provider->user->photo;
        return view('backend.org-service-profile.edit', compact('provider', 'isPicExists', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions'));
    }

    public function show($id)
    {
        $provider = Org::withTrashed()->find($id);
        $navs = $this->navs();
        $countFeedbacks = $provider->feedbacks()->count();

        $view = $provider->trashed() ? 'backend.org-service-profile.show-disabled' : (is_null($provider->expire) ? 'backend.org-service-profile.show-pending' : 'backend.org-service-profile.show');

        return view($view, compact('provider', 'navs', 'countFeedbacks'));
    }

    public function update(Request $request, $id)
    {
        $org = Org::find($id);

        // TODO:: Move this validations to a requests class
        // TODO:: Validation should be more harder, to improve security
        if ($org->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }

        $data = $request->only([
            'name',
            'discription',
            'mobile',
            'email',
            'website',
            'facebook',
            'district',
            'thana',
            'union',
            'address',
            'sub-categories',
            'qualification',
            'nid'
        ]);

        DB::beginTransaction();

        if ($request->post('no-sub-category')) {
            $newSubCategories = [];
            foreach ($request->post('sub-category-requests') as $subCategoryName) {
                !is_null($subCategoryName) && array_push($newSubCategories, [
                    'name' => $subCategoryName,
                    'is_confirmed' => 0
                ]);
            }
            $data['sub-category-requests'] = $org->category
                ->subCategories()
                ->createMany($newSubCategories)
                ->pluck('id')
                ->toArray();
        }

        if ($request->has('no-thana') && $request->post('no-thana') == 'on') {
            $thana = new Thana;
            $thana->district_id = $request->post('district');
            $thana->bn_name = $request->post('thana-request');
            $thana->is_pending = 1;
            $thana->save();
            $data['thana-request'] = $thana->id;
        }

        if ($request->has('no-union') && $request->post('no-union') == 'on') {
            $union = new Union;
            $union->thana_id = $thana->id;
            $union->bn_name = $request->post('union-request');
            $union->is_pending = 1;
            $union->save();
            $data['union-request'] = $union->id;
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('org/' . $org->id . '/' . 'docs');
        }

        if ($request->hasFile('trade-license')) {
            $data['trade-license'] = $request->file('trade-license')->store('org/' . $org->id . '/' . 'docs');
        }

        // work images
        if ($request->has('images') && $request->hasFile('images')) {
            $images = [];

            // TODO:: Validation
            foreach ($request->post('images') as $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && array_push($images, ['description' => $image['description']]);
            }
            foreach ($request->file('images') as $key => $image) {
                (array_key_exists('description', $image) && !is_null($image['description'])) && $images[$key]['path'] = $image['file']->store('ind/' . $ind->id . '/' . 'images');
            }

            $data['images'] = $images;
        }

        // identities
        if ($request->hasFile('identities')) {
            $data['identities'] = [];
            foreach ($request->file('identities') as $identity) {
                array_push($data['identities'], $identity->store('user-photos/' . $org->user->id));
            }
        }

        $edit = new ServiceEdit;
        $edit->service_editable_id = $org->id;
        $edit->service_editable_type = 'org';
        $edit->data = json_encode($data);
        $edit->save();

        DB::commit();
        return back()->with('success', 'সম্পন্ন!');
    }

    private function navs()
    {
        $inds = Auth::user()->inds()->withTrashed()->get();
        $orgs = Auth::user()->orgs()->withTrashed()->get();
        $navs = [];

        foreach ($inds as $ind) {
            array_push($navs, ['url' => route('profile.backend.individual-service.show', $ind->id), 'text' => $ind->category->name]);
        }

        foreach ($orgs as $org) {
            array_push($navs, ['url' => route('profile.backend.organization-service.show', $org->id), 'text' => $org->name]);
        }

        return $navs;
    }
}

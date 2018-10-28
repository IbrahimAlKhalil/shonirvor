<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ind;
use App\Models\Category;
use App\Models\WorkMethod;
use App\Models\ServiceEdit;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateInd;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;

class IndProfileController extends Controller
{

    public function edit($id)
    {
        $provider = Ind::with(['division', 'district', 'thana', 'union'])->find($id);
        // TODO:: Move this validations to requests class
        if ($provider->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }
        if ($provider->edit()->exists()) {
            return back()->with('error', 'ইতোমধ্যেই আপনার প্রোফাইলের একটি সম্পাদনা প্রক্রিয়াধীন আছে');
        }

        $workMethods = WorkMethod::all();
        $categories = Category::getAll('ind')->get();
        $subCategories = SubCategory::onlyInd()->get();
        $divisions = Division::all();
        $districts = $provider->division()->with('districts')->first()->districts;
        $thanas = $provider->district->thanas()->where('is_pending', 0)->get();
        $unions = $provider->thana->unions()->where('is_pending', 0)->get();

        $isPicExists = $provider->user->photo;
        return view('backend.ind-service-profile.edit', compact('provider', 'isPicExists', 'workMethods', 'categories', 'subCategories', 'divisions', 'districts', 'thanas', 'unions'));
    }

    public function show($id)
    {
        $provider = Ind::withTrashed()->find($id);
        $navs = $this->navs();
        $countFeedbacks = $provider->feedbacks()->count();

        $view = $provider->trashed() ? 'backend.ind-service-profile.show-disabled' : ($provider->is_pending == 1 ? 'backend.ind-service-profile.show-pending' : 'backend.ind-service-profile.show');

        return view($view, compact('provider', 'navs', 'countFeedbacks'));
    }

    public function update(UpdateInd $request, $id)
    {
        $ind = Ind::find($id);

        // TODO:: Move this validations to a requests class
        // TODO:: Validation should be more harder, to improve security
        if ($ind->user_id != Auth::id()) {
            return redirect(route('individual-service-registration.index'));
        }

        $data = $request->only([
            'mobile',
            'email',
            'website',
            'facebook',
            'division',
            'district',
            'thana',
            'union',
            'address',
            'sub-categories',
            'work-methods',
            'qualification',
            'nid',
            'identities',
            'experience-certificate',
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
            $data['sub-category-requests'] = $ind->category
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


        if ($request->hasFile('experience-certificate')) {
            $data['experience-certificate'] = $request->file('experience-certificate')->store('ind/' . $ind->id . '/' . 'docs');
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
                array_push($data['identities'], $identity->store('user-photos/' . $ind->user->id));
            }
        }

        $edit = new ServiceEdit;
        $edit->service_editable_id = $ind->id;
        $edit->service_editable_type = 'ind';
        $edit->data = json_encode($data);
        $edit->save();

        DB::commit();
        return back()->with('success', 'সম্পন্ন!');
    }

    public function updateStatus(Request $request)
    {
        // TODO:: Validation will be needed
        $provider = Ind::find($request->post('id'));
        $provider->status = $request->post('message');
        switch ($request->post('is-available')) {
            case 'yes':
                $provider->is_available = 1;
                break;
            case 'no':
                $provider->is_available = 0;
        }
        $provider->save();
        return back()->with('success', 'সম্পন্ন!');
    }

    public function destroy(Request $request, Ind $provider)
    {
        // TODO:: Some validation will be needed, make sure that someone can't reach to this method without permission

        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {

            switch ($request->post('type')) {
                case 'deactivate':
                    $provider->delete();
                    $msg = 'Account Deactivated Successfully!';
                    $route = route('profile.backend.individual-service.show', $provider->id);
                    break;
                default:

                    // TODO:: Delete images/docs after deleting account
                    $user = $provider->user;

                    if (!$user->inds('approved')->count() <= 1) {
                        $user->roles()->detach('ind');
                    }

                    $provider->forceDelete();
                    $msg = 'Account Removed Successfully!';
                    $route = route('backend.home');
            }

            return redirect($route)->with('success', $msg);
        }

        return abort('404');
    }

    private function navs()
    {
        $inds = Auth::user()->inds()->withTrashed()->get();
        $orgs = Auth::user()->orgs()->withTrashed()->get();
        $navs = [];

        foreach ($inds as $ind) {
            array_push($navs, ['url' => route('profile.backend.individual-service.show', $ind->id), 'text' => $ind->category->name, 'after' => '&nbsp;<span class="badge badge-light float-right">ব্যাক্তিগত</span>']);
        }

        foreach ($orgs as $org) {
            array_push($navs, ['url' => route('profile.backend.organization-service.show', $org->id), 'text' => $org->name, 'after' => '&nbsp;<span class="badge badge-light float-right">প্রাতিষ্ঠানিক</span>']);
        }

        return $navs;
    }
}

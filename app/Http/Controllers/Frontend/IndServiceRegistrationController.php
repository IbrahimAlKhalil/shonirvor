<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditPendingIndService;
use App\Http\Requests\StorePendingIndService;
use App\Models\IndCategory;
use App\Models\IndSubCategory;
use App\Models\PendingIndService;
use App\Models\PendingIndServiceDoc;
use App\Models\PendingIndServiceImage;
use App\Models\User;
use App\Models\WorkMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $pendingIndServices = $user->pendingIndServices;

        $workMethods = WorkMethod::all();
        $categories = IndCategory::all();
        $subCategories = IndSubCategory::all();
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'pendingIndServices', 'workMethods', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');
        $view = 'frontend.registration.ind-service.confirm';
        $count = $pendingIndServices->count();

        // check what if current user didn't reach at the maximum pending request
        if ($count >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view($view, $compact);
        }

        // check what if current user has less than 3 pending request
        if ($count < 3 && $count >= 1) {
            $compact['classesToAdd'] = ['active', ''];
            // didn't reach at the maximum
            // redirect them to the confirmation page
            return view($view, $compact);
        }

        // pendingIndServices, classesToAdd are unnecessary for index
        unset($compact['pendingIndServices'], $compact['classesToAdd']);

        return view('frontend.registration.ind-service.index', $compact);
    }


    public function store(StorePendingIndService $request)
    {

        $user = Auth::user();
        $pendingIndServices = $user->pendingIndServices;
        $workMethods = WorkMethod::all();
        $categories = IndCategory::all();
        $subCategories = IndSubCategory::all();
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'pendingIndServices', 'workMethods', 'districts', 'thanas', 'unions', 'isPicExists', 'categories', 'subCategories');

        // check what if current user didn't reach at the maximum pending request
        if ($pendingIndServices->count() >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.ind-service.confirm', $compact);
        }

        $pendingIndService = new PendingIndService;

        // PendingIndService

        $pendingIndService->user_id = $user->id;
        $pendingIndService->district_id = $request->post('district');
        $pendingIndService->thana_id = $request->post('thana');
        $pendingIndService->union_id = $request->post('union');
        if ($request->has('category')) {
            $pendingIndService->ind_category_id = $request->post('category');
        }

        $pendingIndService->mobile = $request->post('mobile');
        $pendingIndService->email = $request->post('email');
        $pendingIndService->website = $request->post('website');
        $pendingIndService->facebook = $request->post('facebook');
        $pendingIndService->no_area = $request->post('no_area');
        $pendingIndService->address = $request->post('address');
        $pendingIndService->save();

        // ind_category_pending_ind_service table
        if ($request->has('sub-categories')) {
            $subCategories = [];
            foreach ($request->post('sub-categories') as $subCategory) {
                array_push($subCategories, [
                    'ind_sub_category_id' => $subCategory,
                    'pending_ind_service_id' => $pendingIndService->id
                ]);
            }
        }
        DB::table('ind_sub_category_pending_ind_service')->insert($subCategories);

        // work_method_pending_ind_service table
        $workMethods = [];
        foreach ($request->post('work-methods') as $workMethod) {
            array_push($workMethods, [
                'work_method_id' => $workMethod,
                'pending_ind_service_id' => $pendingIndService->id
            ]);
        }
        DB::table('work_method_pending_ind_service')->insert($workMethods);

        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->age = $request->post('age');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->age = $request->post('age');
        $user->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('pending-ind-images'),
                    'pending_ind_service_id' => $pendingIndService->id
                ]);
            }

            PendingIndServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('pending-ind-docs'),
                    'pending_ind_service_id' => $pendingIndService->id
                ]);
            }

            PendingIndServiceDoc::insert($documents);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');
    }

    public function update(StoreEditPendingIndService $request, $id)
    {
        $pendingIndService = PendingIndService::find($id);
        $user = Auth::user();

        $pendingIndService->user_id = $user->id;
        $pendingIndService->email = $request->post('email');
        $pendingIndService->mobile = $request->post('mobile');
        $pendingIndService->latitude = $request->post('latitude');
        $pendingIndService->longitude = $request->post('longitude');
        $pendingIndService->service = $request->post('service');
        $pendingIndService->address = $request->post('address');
        $pendingIndService->save();

        $user->name = $request->post('name');
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');

        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photo');
        }

        $user->age = $request->post('age');

        $user->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('pending-ind-images'),
                    'pending_ind_service_id' => $pendingIndService->id
                ]);
            }

            PendingIndServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('pending-ind-docs'),
                    'pending_ind_service_id' => $pendingIndService->id
                ]);
            }

            PendingIndServiceDoc::insert($documents);
        }

        $pendingIndService->save();

        return back()->with('success', 'Done!');
    }

    public function edit($id)
    {
        $pendingIndService = PendingIndService::find($id);
        $workMethods = WorkMethod::all();
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();

        $isPicExists = $pendingIndService->user->photo;
        return view('frontend.registration.ind-service.edit', compact('pendingIndService', 'isPicExists', 'workMethods', 'districts', 'thanas', 'unions'));
    }
}
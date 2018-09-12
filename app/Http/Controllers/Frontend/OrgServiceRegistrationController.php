<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditPendingOrgService;
use App\Http\Requests\StorePendingOrgService;
Use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceDoc;
use App\Models\PendingOrgServiceImage;
use Illuminate\Support\Facades\Auth;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $pendingOrgServices = $user->pendingOrgServices;
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'pendingOrgServices', 'districts', 'thanas', 'unions', 'isPicExists');

        $count = $pendingOrgServices->count();

        // check what if current user didn't reach at the maximum pending request
        if ($count >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', $compact);
        }

        // check what if current user has less than 3 pending request
        if ($count < 3 && $count >= 1) {
            $compact['classesToAdd'] = ['active', ''];

            // didn't reach at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', $compact);
        }

        // user didn't make any request for being organizational service provider
        return view('frontend.registration.org-service.index', $compact);
    }

    public function store(StorePendingOrgService $request)
    {
        $user = Auth::user();
        $pendingOrgServices = $user->pendingOrgServices;
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $classesToAdd = ['active', 'disabled'];
        $isPicExists = $user->photo;
        $compact = compact('classesToAdd', 'pendingOrgServices', 'districts', 'thanas', 'unions', 'isPicExists');

        $count = $pendingOrgServices->count();

        // check what if current user didn't reach at the maximum pending request
        if ($count >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', $compact);
        }

        $pendingOrgService = new PendingOrgService;

        $user = Auth::user();

        $pendingOrgService->user_id = Auth::id();
        $pendingOrgService->district_id = $request->post('district');
        $pendingOrgService->thana_id = $request->post('thana');
        $pendingOrgService->union_id = $request->post('union');

        $pendingOrgService->org_name = $request->post('org-name');
        $pendingOrgService->mobile = $request->post('mobile');
        $pendingOrgService->category = $request->post('category');
        $pendingOrgService->website = $request->post('website');
        $pendingOrgService->facebook = $request->post('facebook');
        $pendingOrgService->description = $request->post('description');
        $pendingOrgService->no_area = $request->post('no_area');
        $pendingOrgService->email = $request->post('email');
        $pendingOrgService->address = $request->post('address');
        if ($request->hasFile('logo')) {
            $pendingOrgService->logo = $request->file('logo')->store('pending-org-images');
        }
        $pendingOrgService->save();


        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->age = $request->post('age');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();


        if ($request->has('images')) {
            $images = [];

            foreach ($request->images as $image) {
                array_push($images, [
                    'image' => $image->store('pending-org-images'),
                    'pending_org_service_id' => $pendingOrgService->id
                ]);
            }

            PendingOrgServiceImage::insert($images);

        }

        if ($request->has('docs')) {
            $docs = [];

            foreach ($request->docs as $doc) {
                array_push($docs, [
                    'doc' => $doc->store('pending-org-docs'),
                    'pending_org_service_id' => $pendingOrgService->id
                ]);
            }

            PendingOrgServiceDoc::insert($docs);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');
    }

    public function edit($id)
    {
        $pendingOrgService = PendingOrgService::find($id);
        $districts = District::take(20)->get();
        $thanas = Thana::take(20)->get();
        $unions = Union::take(20)->get();
        $isPicExists = $pendingOrgService->user->photo;

        return view('frontend.registration.org-service.edit', compact('pendingOrgService', 'districts', 'thanas', 'unions', 'isPicExists'));
    }


    public function update(StoreEditPendingOrgService $request, $id)
    {

        $pendingOrgService = PendingOrgService::find($id);

        $user = Auth::user();

        $pendingOrgService->district_id = $request->post('district');
        $pendingOrgService->thana_id = $request->post('thana');
        $pendingOrgService->union_id = $request->post('union');

        $pendingOrgService->org_name = $request->post('org-name');
        $pendingOrgService->mobile = $request->post('mobile');
        $pendingOrgService->category = $request->post('category');
        $pendingOrgService->website = $request->post('website');
        $pendingOrgService->facebook = $request->post('facebook');
        $pendingOrgService->description = $request->post('description');
        $pendingOrgService->no_area = $request->post('no_area');
        $pendingOrgService->email = $request->post('email');
        $pendingOrgService->address = $request->post('address');
        if ($request->hasFile('logo')) {
            $pendingOrgService->logo = $request->file('logo')->store('pending-org-images');
        }
        $pendingOrgService->save();


        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->age = $request->post('age');
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('user-photos');
        }
        $user->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('pending-org-images'),
                    'pending_org_service_id' => $pendingOrgService->id
                ]);
            }

            PendingOrgServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('pending-org-docs'),
                    'pending_org_service_id' => $pendingOrgService->id
                ]);
            }

            PendingOrgServiceDoc::insert($documents);
        }

        $pendingOrgService->save();

        return back()->with('success', 'Done!');
    }
}

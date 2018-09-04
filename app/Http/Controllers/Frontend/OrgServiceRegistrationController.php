<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditPendingOrgService;
use App\Http\Requests\StorePendingOrgService;
use App\Models\OrgService;
Use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceDoc;
use App\Models\PendingOrgServiceImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrgServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $pendingOrgServices = $user->pendingOrgService;
        $classesToAdd = ['active', 'disabled'];

        // check what if current user didn't reach at the maximum pending request
        if ($pendingOrgServices->count() >= 3) {
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', compact('classesToAdd', 'pendingOrgServices'));
        }

        // check what if current user has less than 3 pending request
        if ($pendingOrgServices->count() < 3 && $pendingOrgServices->count() >= 1) {
            $classesToAdd = ['', 'active'];
            // didn't reach at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', compact('classesToAdd', 'pendingOrgServices'));
        }

        // user didn't make any request for being organizational service provider
        return view('frontend.registration.org-service.index');
    }

    public function store(StorePendingOrgService $request)
    {
        $user = Auth::user();
        $pendingOrgServices = $user->pendingOrgService;

        // check what if current user didn't reach at the maximum pending request
        if ($pendingOrgServices->count() >= 3) {
            $classesToAdd = ['active', 'disabled'];
            // reached at the maximum
            // redirect them to the confirmation page
            return view('frontend.registration.org-service.confirm', compact('classesToAdd', 'pendingOrgServices'));
        }

        $pendingOrgService = new PendingOrgService;

        $user = Auth::user();

        $pendingOrgService->user_id = Auth::id();
        $pendingOrgService->org_name = $request->post('org-name');
        $pendingOrgService->mobile = $request->post('mobile');
        $pendingOrgService->description = $request->post('description');
        $pendingOrgService->email = $request->post('email');
        $pendingOrgService->latitude = $request->post('latitude');
        $pendingOrgService->longitude = $request->post('longitude');
        $pendingOrgService->service = $request->post('service');
        $pendingOrgService->address = $request->post('address');
        $pendingOrgService->save();


        $user->name = $request->post('name');
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->photo = $request->file('photo')->store('user-photo');
        $user->age = $request->post('age');

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
        return view('frontend.registration.org-service.edit', compact('pendingOrgService'));
    }


    public function update(StoreEditPendingOrgService $request, $id)
    {

        $pendingOrgService = PendingOrgService::find($id);

        $pendingOrgService->mobile = $request->post('mobile');
        $pendingOrgService->org_name = $request->post('org-name');
        $pendingOrgService->description = $request->post('description');
        $pendingOrgService->email = $request->post('email');
        $pendingOrgService->latitude = $request->post('latitude');
        $pendingOrgService->longitude = $request->post('longitude');
        $pendingOrgService->service = $request->post('service');

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

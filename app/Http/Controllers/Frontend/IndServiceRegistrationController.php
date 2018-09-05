<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEditPendingIndService;
use App\Http\Requests\StorePendingIndService;
use App\Models\PendingIndService;
use App\Models\PendingIndServiceDoc;
use App\Models\PendingIndServiceImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class IndServiceRegistrationController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Check what if the user is already a service provider.
        if ($user->hasRole(3)) {

            // user is already a service provider so stop going farther
            // TODO::1 Make a nice view an show, the message there, or redirect to another specific route

            return response('You are already a service provider, if you are planing provide other services, then please request for adding new Service Category to your account.');
        }

        $pendingIndService = $user->pendingIndService;

        // check what if current user has already a request pending
        if ($pendingIndService) {

            // user has already a request pending so let him/her edit the information
            return view('frontend.registration.ind-service.edit', compact('pendingIndService'));
        }

        $isPicExists = $user->photo;

        return view('frontend.registration.ind-service.index', compact('isPicExists'));
    }


    public function store(StorePendingIndService $request)
    {

        $user = Auth::user();

        // Check what if the user is already a service provider.

        if ($user->hasRole(3)) {
            // user is already a service provider so stop going farther

            // TODO::1
            return response('You are already a service provider, if you are planing provide other services, then please request for adding new Service Category to your account.');
        }

        $pendingIndService = $user->pendingIndService;

        // check what if current user has already a request pending
        if ($pendingIndService) {

            // user has already a request pending so let him/her edit the information
            return view('frontend.registration.ind-service.edit', compact('pendingIndService'));
        }

        $pendingIndService = new PendingIndService;
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
}
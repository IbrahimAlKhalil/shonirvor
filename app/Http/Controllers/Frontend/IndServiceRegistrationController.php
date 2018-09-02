<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndServiceRegistration;
use App\Models\IndService;
use App\Models\PendingIndService;
use App\Models\PendingIndServiceDoc;
use App\Models\PendingIndServiceImage;
use Illuminate\Support\Facades\Auth;

class IndServiceRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.registration.ind-service');
    }


    public function store(StoreIndServiceRegistration $request)
    {
        if(IndService::where('user_id', Auth::id())->exists()) {
            return response('You are already a service provider, if you are planing provide other services, then please request for adding new Service Category to your account.');
        }

        if (PendingIndService::where('user_id', Auth::id())->exists()) {
            return response('You have already requested for this service, wait until we review you application!');
        }

        $registration = new PendingIndService;
        $user = Auth::user();

        $registration->user_id = Auth::id();
        $registration->email = $request->post('email');
        $registration->mobile = $request->post('mobile');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');

        $registration->save();

        $user->name = $request->post('name');
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->photo = $request->file('photo')->store('user-photo');
        $user->age = $request->post('age');

        $user->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('pending-ind-images'),
                    'pending_ind_service_id' => $registration->id
                ]);
            }

            PendingIndServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('pending-ind-docs'),
                    'pending_ind_service_id' => $registration->id
                ]);
            }

            PendingIndServiceDoc::insert($documents);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');

    }

}

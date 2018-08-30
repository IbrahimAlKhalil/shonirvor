<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndServiceProviderRegistration;
use App\Models\IndServiceProviderRegistration;
use App\Models\IndServiceProviderRegistrationDocument;
use App\Models\IndServiceProviderRegistrationPhoto;
use Illuminate\Support\Facades\Auth;

class IndServiceProviderRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.registration.ind-service-provider');
    }


    public function store(StoreIndServiceProviderRegistration $request)
    {
        $registration = new IndServiceProviderRegistration;
        $registration->user_id = Auth::id();
        $registration->email = $request->post('email');
        $registration->age = $request->post('age');
        $registration->qualification = $request->post('qualification');
        $registration->nid = $request->post('nid');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');

        $registration->save();


        if ($request->has('photos')) {
            $photos = [];
            foreach ($request->photos as $photo) {
                array_push($photos, [
                    'photo' => $photo->store('pending-ind-photos'),
                    'ind_service_provider_registration_id' => $registration->id
                ]);
            }

            IndServiceProviderRegistrationPhoto::insert($photos);
        }

        if ($request->has('documents')) {
            $documents = [];

            foreach ($request->documents as $document) {
                array_push($documents, [
                    'document' => $document->store('pending-ind-docs'),
                    'ind_service_provider_registration_id' => $registration->id
                ]);
            }

            IndServiceProviderRegistrationDocument::insert($documents);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');

    }

    public function approve()
    {

    }

    public function reject()
    {

    }

}

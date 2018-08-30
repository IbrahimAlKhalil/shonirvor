<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrgServiceProviderRegistration;
use App\Models\OrgServiceProviderRegistration;
use App\Models\OrgServiceProviderRegistrationDocument;
use App\Models\OrgServiceProviderRegistrationPhoto;
use Illuminate\Support\Facades\Auth;

class OrgServiceProviderRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.registration.org-service-provider');
    }

    public function store(StoreOrgServiceProviderRegistration $request)
    {
        $registration = new OrgServiceProviderRegistration;
        $registration->user_id = Auth::id();
        $registration->name = $request->post('name');
        $registration->email = $request->post('email');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');
        $registration->save();

        if ($request->has('photos')) {
            $photos = [];

            foreach ($request->photos as $photo) {
                array_push($photos, [
                    'photo' => $photo->store('pending-org-photos'),
                    'org_service_provider_registration_id' => $registration->id
                ]);
            }

            OrgServiceProviderRegistrationPhoto::insert($photos);

        }

        if ($request->has('documents')) {
            $documents = [];

            foreach ($request->documents as $document) {
                array_push($documents, [
                    'document' => $document->store('pending-org-photos'),
                    'org_service_provider_registration_id' => $registration->id
                ]);
            }

            OrgServiceProviderRegistrationDocument::insert($documents);
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

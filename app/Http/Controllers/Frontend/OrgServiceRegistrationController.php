<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrgServiceRegistration;
use App\Models\OrgService;
Use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceDoc;
use App\Models\PendingOrgServiceImage;
use Illuminate\Support\Facades\Auth;

class OrgServiceRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.registration.org-service');
    }

    public function store(StoreOrgServiceRegistration $request)
    {
        if(PendingOrgService::where('user_id', Auth::id())->count() >= 3) {
            return response('You have already three requests
             pending, please wait until we review your applications, and then you can request to be a service provider again!');
        }

        $registration = new PendingOrgService;

        $user = Auth::user();

        $registration->user_id = Auth::id();
        $registration->org_name = $request->post('org-name');
        $registration->mobile = $request->post('mobile');
        $registration->description = $request->post('description');
        $registration->email = $request->post('email');
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

            foreach ($request->images as $image) {
                array_push($images, [
                    'image' => $image->store('pending-org-images'),
                    'pending_org_service_id' => $registration->id
                ]);
            }

            PendingOrgServiceImage::insert($images);

        }

        if ($request->has('docs')) {
            $docs = [];

            foreach ($request->docs as $doc) {
                array_push($docs, [
                    'doc' => $doc->store('pending-org-docs'),
                    'pending_org_service_id' => $registration->id
                ]);
            }

            PendingOrgServiceDoc::insert($docs);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');
    }
}

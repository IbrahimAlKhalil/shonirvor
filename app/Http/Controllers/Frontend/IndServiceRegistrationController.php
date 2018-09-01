<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndServiceRegistration;
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

        $registration = new PendingIndService;

        $registration->user_id = Auth::id();
        $registration->email = $request->post('email');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');


        $registration->user()->update([
            'name' => $request->post('name'),
            'nid' => $request->post('nid'),
            'photo' => $request->file('photo')->store('user-photo'),
            'age' => $request->post('age')
        ]);

        $registration->save();


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

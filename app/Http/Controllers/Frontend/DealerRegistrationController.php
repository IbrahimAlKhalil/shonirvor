<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreDealerRegistration;
use App\Models\DealerRegistration;
use App\Models\DealerRegistrationDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DealerRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.registration.dealer');
    }


    public function store(StoreDealerRegistration $request)
    {
        $newDealerRegistration = new DealerRegistration();

        $newDealerRegistration->user_id = Auth::id();

        $newDealerRegistration->email = $request->post('email');
        $newDealerRegistration->age = $request->post('age');
        $newDealerRegistration->nid = $request->post('nid');
        $newDealerRegistration->address = $request->post('address');
        $newDealerRegistration->photo = $request->file('photo')->store('pending-dealers');
        $newDealerRegistration->save();


        if ($request->has('documents')) {
            $documents = [];

            foreach ($request->documents as $document) {
                array_push($documents, [
                    'document' => $document->store('pending-dealers'),
                    'dealer_registration_id' => $newDealerRegistration->id
                ]);
            }

            DealerRegistrationDocument::insert($documents);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');

    }
}

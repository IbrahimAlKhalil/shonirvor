<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealerRegistration;
use App\Models\DealerRegistration;
use Illuminate\Http\Request;

class DealerRegistrationController extends Controller
{

    public function index()
    {
        return view('frontend.dealer-registration');
    }


    public function dealerRequests(Request $request)
    {
        $dealerRequests = DealerRegistration::all();

        return view('backend.dealer-requests', compact('dealerRequests'));
    }


    public function store(StoreDealerRegistration $request)
    {

        $newDealerRegistration = new DealerRegistration();

        $newDealerRegistration->name = $request->post('name');
        $newDealerRegistration->number = $request->post('number');
        $newDealerRegistration->email = $request->post('email');
        $newDealerRegistration->age = $request->post('age');
        $newDealerRegistration->qualification = $request->post('qualification');
        $newDealerRegistration->address = $request->post('address');
        $newDealerRegistration->photo = $request->file('photo')->store('pending-dealers');
        $newDealerRegistration->save();

        return back()->with('success', true);
        /*
        return view('frontend.dealer-registration', ['success' => true]);*/

    }


    public function destroy(DealerRegistration $dealerRegistration)
    {
        //
    }
}

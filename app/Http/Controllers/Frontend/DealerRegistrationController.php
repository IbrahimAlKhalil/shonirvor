<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDealerRegistration;
use App\Models\DealerRegistration;
use Illuminate\Http\Request;

class DealerRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.dealer-registration');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        return view('frontend.dealer-registration', ['success' => true]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DealerRegistration $dealerRegistration
     * @return \Illuminate\Http\Response
     */
    public function show(DealerRegistration $dealerRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DealerRegistration $dealerRegistration
     * @return \Illuminate\Http\Response
     */
    public function edit(DealerRegistration $dealerRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\DealerRegistration $dealerRegistration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DealerRegistration $dealerRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DealerRegistration $dealerRegistration
     * @return \Illuminate\Http\Response
     */
    public function destroy(DealerRegistration $dealerRegistration)
    {
        //
    }
}

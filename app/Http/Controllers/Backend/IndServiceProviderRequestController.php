<?php

namespace App\Http\Controllers\Backend;

use App\Models\IndServiceProviderRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndServiceProviderRequestController extends Controller
{

    public function index()
    {
        $serviceProviderRequests = IndServiceProviderRegistration::paginate(15);

        return view('backend.ind-service-provider-request.index', compact('serviceProviderRequests'));
    }


    public function show($id)
    {
        //
    }

}

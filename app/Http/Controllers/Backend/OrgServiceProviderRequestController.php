<?php

namespace App\Http\Controllers\Backend;

use App\Models\OrgServiceProviderRegistration;
use App\Http\Controllers\Controller;

class OrgServiceProviderRequestController extends Controller
{
    public function index()
    {
        $serviceProviderRequests = OrgServiceProviderRegistration::paginate(15);

        return view('backend.org-service-provider-request.index', compact('serviceProviderRequests'));
    }


    public function show($id)
    {

    }
}

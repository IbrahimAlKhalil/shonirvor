<?php

namespace App\Http\Controllers\Backend;

use App\Models\DealerRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealerRequestController extends Controller
{

    public function index()
    {
        $dealerRequests = DealerRegistration::paginate(3);

        return view('backend.dealer-request.index', compact('dealerRequests'));
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $dealerRequest = DealerRegistration::find($id);

        return view('backend.dealer-request.show', compact('dealerRequest'));
    }


    public function destroy(DealerRegistration $dealerRegistration)
    {
        //
    }
}

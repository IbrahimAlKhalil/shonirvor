<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndTopServiceRequestController extends Controller
{
    public function show(Income $application)
    {
        if ($application->approved == 1) abort(404);

        return view('backend.request.top-service.ind', compact('application'));
    }

    public function update(Request $request, Income $application)
    {
        dd($request->all());
    }
}

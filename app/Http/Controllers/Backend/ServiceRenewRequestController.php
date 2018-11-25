<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceRenewRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function show(Income $application)
    {
        if ($application->approved) abort(404);

        $application->load([
            'paymentMethod',
            'package.properties',
            'incomeable'
        ]);

        $service = $application->incomeable;

        $user = Auth::user();
        $properties = $application->package->properties->groupBy('name');

        return view('backend.request.service-renew', compact('service', 'user', 'properties', 'application'));
    }

    public function update(Income $application)
    {

        DB::beginTransaction();

        $application->load([
            'package.properties',
            'incomeable'
        ]);
        $service = $application->incomeable;

        $duration = $application->package->properties->groupBy('name')['duration'][0]->value;

        $service->expire = $service->expire->addDays($duration);
        $service->save();

        $application->approved = 1;
        $application->save();

        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }

    public function destroy(Income $application)
    {
        DB::beginTransaction();
        $application->delete();
        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }
}

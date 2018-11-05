<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ad;
use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdRequestController extends Controller
{
    public function show(Income $application)
    {
        $application->load([
            'incomeable',
            'paymentMethod',
            'package.properties' => function ($query) {
                $query->where('name', 'name');
            }
        ]);

        $ad = $application->incomeable;


        if ($application->approved) {
            abort(404);
        }

        $user = Auth::user();
        $properties = $application->package->properties->groupBy('name');

        return view('backend.request.ad', compact('ad', 'user', 'properties', 'application'));
    }

    public function update(Income $application)
    {
        // TODO: Check whether ad model and the payment model is related or not

        $application->load([
            'package.properties',
            'incomeable'
        ]);
        DB::beginTransaction();

        $ad = $application->incomeable;
        $duration = $application->package->properties->groupBy('name')['duration'][0]->value;

        $ad->expire = now()->addDays($duration)->format('Y-m-d H:i:s');
        $ad->save();

        $application->approved = 1;
        $application->save();

        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }

    public function destroy(Income $application)
    {
        DB::beginTransaction();

        // TODO: Delete Image
        $application->delete();

        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }
}

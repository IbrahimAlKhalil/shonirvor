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
    public function show(Ad $ad)
    {
        $ad->load([
            'payments' => function ($query) {
                $query->with([
                    'paymentMethod',
                    'package.properties'
                ]);
            }
        ]);

        $payment = $ad->payments->first();

        if (!$payment) {
            abort(404);
        }

        if ($payment->approved) {
            abort(404);
        }

        $user = Auth::user();
        $properties = $payment->package->properties->groupBy('name');

        return view('backend.request.ad', compact('ad', 'user', 'properties', 'payment'));
    }

    public function update(Request $request, Ad $ad)
    {
        DB::beginTransaction();

        $payment = Income::with('package.properties')->find($request->post('payment'));
        $duration = $payment->package->properties->groupBy('name')['duration'][0]->value;

        $ad->expire = now()->addDays($duration)->format('Y-m-d H:i:s');
        $ad->save();

        $payment->approved = 1;
        $payment->save();

        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }

    public function destroy(Ad $ad, Request $request)
    {
        DB::beginTransaction();

        // TODO: Delete Image
        DB::table('incomes')->where('id', $request->post('payment'))->delete();
        $ad->delete();

        DB::commit();

        // TODO: Redirect to appropriate page
        return response('Done!');
    }
}

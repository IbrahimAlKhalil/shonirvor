<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndTopServiceRequestController extends Controller
{
    private $packageTypeId = 3;

    public function show(Income $application)
    {
        $application->load([
            'incomeable.user',
            'package.properties'
        ]);

        if ($application->approved == 1 ||  $application->package->package_type_id != $this->packageTypeId)
            abort(404);

        return view('backend.request.ind-top-service', compact('application'));
    }

    public function update(Income $application)
    {
        $application->load([
            'incomeable',
            'package.properties'
        ]);

        $packageDuration = $application->package->properties->where('name', 'duration')->first()->value;
        $currentExpire = $application->incomeable->top_expire;

        if ($currentExpire != null && $currentExpire->isFuture()) {
            $newExpire = $currentExpire->addDays($packageDuration);
        } else {
            $newExpire = now()->addDays($packageDuration);
        }

        DB::beginTransaction();

        $application->approved = 1;
        $application->save();

        $application->incomeable->top_expire = $newExpire;
        $application->incomeable->save();

        DB::commit();

        return redirect('dashboard/requests?type=ind-top')->with('success', 'বেক্তিগত টপ সার্ভিস রিকোয়েস্টটি গ্রহণ করা হয়েছে।');
    }

    public function destroy(Income $application)
    {
        $application->delete();

        return redirect('dashboard/requests?type=ind-top')->with('success', 'বেক্তিগত টপ সার্ভিস রিকোয়েস্টটি মুছে ফেলা হয়েছে।');
    }
}
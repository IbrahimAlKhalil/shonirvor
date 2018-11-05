<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrgTopServiceRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:org-top-service-request,application');
    }

    public function show(Income $application)
    {
        $application->load([
            'incomeable',
            'package.properties'
        ]);

        return view('backend.request.org-top-service', compact('application'));
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

        return redirect('dashboard/requests?type=org-top')->with('success', 'প্রাতিষ্ঠানিক টপ সার্ভিস রিকোয়েস্টটি গ্রহণ করা হয়েছে।');
    }

    public function destroy(Income $application)
    {
        $application->delete();

        return redirect('dashboard/requests?type=ind-top')->with('success', 'প্রাতিষ্ঠানিক টপ সার্ভিস রিকোয়েস্টটি মুছে ফেলা হয়েছে।');
    }
}
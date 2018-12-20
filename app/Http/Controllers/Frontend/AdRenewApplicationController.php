<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreAdRenewApplication;
use App\Models\Ad;
use App\Models\Income;
use App\Models\Package;
use App\Models\PaymentMethod;
use App\Http\Middleware\AdRenew;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdRenewApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware(AdRenew::class);
    }


    public function show(Ad $ad)
    {
        $packages = Package::onlyAd()->get();
        $paymentMethods = PaymentMethod::all();

        $oldApplication = Income::with([
            'paymentMethod:id,name',
            'package.properties' => function ($query) {
                $query->where('package_property_id', 1);
            }])
            ->where([
                ['incomes.incomeable_type', 'ad'],
                ['incomes.approved', 0]
            ])
            ->where('incomes.incomeable_id', $ad->id)
            ->whereIn('incomes.package_id', $packages->pluck('id')->toArray())
            ->first();

        $application = $ad->payments()->orderByDesc('updated_at')->first();
        return view('frontend.applications.ad.renew.show', compact('packages', 'paymentMethods', 'oldApplication', 'ad', 'application'));
    }

    public function edit(Ad $ad)
    {
        $ad->load([
            'payments' => function ($query) {
                $query->orderByDesc('updated_at');
            }
        ]);
        $application = $ad->payments->first();

        $packages = Package::onlyAd()->get();
        $paymentMethods = PaymentMethod::all();
        return view('frontend.applications.ad.renew.edit', compact('application', 'packages', 'paymentMethods', 'ad'));
    }

    public function update(Ad $ad, StoreAdRenewApplication $request)
    {
        $application = $ad->payments()->orderByDesc('updated_at')->first();

        DB::beginTransaction();

        $message = 'আপনার বিজ্ঞাপন রিনিউ আবেদনটি এডিট করা হয়ছে';
        if ($application->approved) {
            $application = new Income;
            $application->incomeable_id = $ad->id;
            $application->incomeable_type = 'ad';

            $message = 'আপনার বিজ্ঞাপন রিনিউ আবেদনটি জমা রাখা হয়েছে। অতি শিগ্রই এডমিন আপনার আবেদনটি রিভিউ করে এপ্রুভ করবে।';
        }

        $application->package_id = $request->input('package');
        $application->payment_method_id = $request->input('payment-method');
        $application->from = $request->input('from');
        $application->transactionId = $request->input('transaction-id');
        $application->save();

        DB::commit();
        return redirect(route('frontend.applications.ad-renew.show', $ad->id))->with('success', $message);
    }
}

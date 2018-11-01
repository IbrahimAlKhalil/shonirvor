<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreAdApplication;
use App\Models\Ad;
use App\Models\Income;
use App\Models\Package;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdApplicationController extends Controller
{
    private $packages = null;
    private $paymentMethods = null;

    public function __construct()
    {
        $this->middleware('can:ad-application.create,application', ['only' => ['store']]);
        $this->middleware('can:ad-application.update,application', ['only' => ['edit', 'update']]);

        $this->packages = Package::onlyAd()->get();
        $this->paymentMethods = PaymentMethod::all();
    }

    public function index()
    {
        $ad = Ad::onlyPending()->where('user_id', Auth::id())->first();
        $packages = $this->packages;
        $paymentMethods = $this->paymentMethods;
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

        return view('frontend.applications.ad.index', compact('packages', 'paymentMethods', 'oldApplication'));
    }


    public function store(StoreAdApplication $request)
    {
        DB::beginTransaction();
        $ad = new Ad;
        $ad->user_id = Auth::id();
        $ad->image = $request->file('image')->store('user-photos/' . Auth::id());
        $ad->url = $request->post('url');
        $ad->save();

        $application = new Income;
        $application->package_id = $request->input('package');
        $application->payment_method_id = $request->input('payment-method');
        $application->incomeable_id = $ad->id;
        $application->incomeable_type = 'ad';
        $application->from = $request->input('from');
        $application->transactionId = $request->input('transaction-id');
        $application->save();
        DB::commit();

        return back()->with('success', 'বিজ্ঞাপনের জন্য আপনার আবেদনটি গ্রহণ করা হয়েছে। অতি শিগ্রই এডমিন আপনার আবেদনটি রিভিউ করে এপ্রুভ করবে।');
    }


    public function edit(Income $application)
    {

        $ad = $application->incomeable;

        $packages = $this->packages;
        $paymentMethods = $this->paymentMethods;
        return view('frontend.applications.ad.edit', compact('application', 'packages', 'services', 'paymentMethods', 'ad'));
    }

    public function update(Request $request, Income $application)
    {
        DB::beginTransaction();
        $ad = $application->incomeable;
        if ($request->hasFile('image')) {
            $ad->image = $request->file('image')->store('user-photos/' . Auth::id());
        }
        $ad->url = $request->post('url');
        $ad->save();

        $application->package_id = $request->input('package');
        $application->payment_method_id = $request->input('payment-method');
        $application->from = $request->input('from');
        $application->transactionId = $request->input('transaction-id');
        $application->save();


        DB::commit();

        return redirect(route('frontend.applications.ad.index'))->with('success', 'আপনার টপ সার্ভিস আবেদনটি এডিট হয়েছে। অতি শিগ্রই এডমিন আপনার আবেদনটি রিভিউ করে এপ্রুভ করবে।');
    }
}

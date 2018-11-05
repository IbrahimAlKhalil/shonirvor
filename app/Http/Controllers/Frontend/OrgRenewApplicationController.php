<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Models\Income;
use App\Models\Package;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrgRenewApplication;

class OrgRenewApplicationController extends Controller
{
    private $packageTypeId = 2;
    private $packages, $paymentMethods;

    public function __construct()
    {
        $this->middleware('provider');
        $this->middleware('can:org-renew-application.create,application', ['only' => 'store']);
        $this->middleware('can:org-renew-application.update,application', ['only' => ['edit', 'update']]);

        $this->packages = Package::with('properties')
            ->where('package_type_id', $this->packageTypeId)
            ->get();

        $this->paymentMethods = DB::table('payment_methods')->get();
    }

    public function index()
    {

        $services = Org::with('category')
            ->where('user_id', Auth::id())
            ->get();

        $packages = $this->packages;
        $paymentMethods = $this->paymentMethods;

        $oldApplication = Income::with([
            'incomeable.category:id,name',
            'paymentMethod:id,name',
            'package.properties' => function ($query) {
                $query->where('package_property_id', 1);
            }])
            ->where([
                ['incomes.incomeable_type', 'org'],
                ['incomes.approved', 0]
            ])
            ->whereIn('incomes.incomeable_id', $services->pluck('id')->toArray())
            ->whereIn('incomes.package_id', $this->packages->pluck('id')->toArray())
            ->first();

        return view('frontend.applications.org-service.index', compact('packages', 'services', 'paymentMethods', 'oldApplication'));
    }

    public function store(StoreOrgRenewApplication $request)
    {
        DB::beginTransaction();

        $application = new Income;
        $application->package_id = $request->input('package');
        $application->payment_method_id = $request->input('payment-method');
        $application->incomeable_id = $request->input('service');
        $application->incomeable_type = 'org';
        $application->from = $request->input('from');
        $application->transactionId = $request->input('transaction-id');
        $application->save();

        DB::commit();

        return back()->with('success', 'সার্ভিস রিনিউ এর জন্য আপনার আবেদনটি গ্রহণ করা হয়েছে। অতি শিগ্রই এডমিন আপনার আবেদনটি রিভিউ করে এপ্রুভ করবে।');
    }

    public function edit(Income $application)
    {
        $application->load('incomeable.category');

        $services = Org::with('category')
            ->where('user_id', Auth::id())
            ->get();

        $packages = $this->packages;
        $paymentMethods = $this->paymentMethods;

        return view('frontend.applications.org-service.edit', compact('application', 'packages', 'services', 'paymentMethods'));
    }

    public function update(StoreOrgRenewApplication $request, Income $application)
    {
        DB::beginTransaction();

        $application->package_id = $request->input('package');
        $application->payment_method_id = $request->input('payment-method');
        $application->incomeable_id = $request->input('service');
        $application->incomeable_type = 'org';
        $application->from = $request->input('from');
        $application->transactionId = $request->input('transaction-id');
        $application->save();

        DB::commit();

        return redirect(route('frontend.applications.org-service.index'))->with('success', 'আপনার সার্ভিস রিনিউ আবেদনটি এডিট হয়েছে। অতি শিগ্রই এডমিন আপনার আবেদনটি রিভিউ করে এপ্রুভ করবে।');
    }
}

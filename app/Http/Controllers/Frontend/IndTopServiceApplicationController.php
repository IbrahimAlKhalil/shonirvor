<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndTopServiceApplicationController extends Controller
{
    private $packageTypeId = 3;

    public function index()
    {
        $packages = Package::with('properties')
            ->where('package_type_id', $this->packageTypeId)
            ->get();

        $services = Ind::with('category')
            ->where('user_id', Auth::id())
            ->get();

        $paymentMethods = DB::table('payment_methods')->get();

        return view('frontend.applications.top-service.ind', compact('packages', 'services', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function update(Request $request, $id)
    {
        //
    }
}
<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferrerPackageController extends Controller
{
    public function index()
    {
        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.top-service.index'), 'text' => 'টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        return view('backend.packages.referrer.index', compact('navs'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Package $package)
    {
        //
    }

    public function edit(Package $package)
    {
        //
    }

    public function update(Request $request, Package $package)
    {
        //
    }

    public function destroy(Package $package)
    {
        //
    }
}

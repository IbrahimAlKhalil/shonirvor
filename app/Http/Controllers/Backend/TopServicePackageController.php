<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TopServicePackageController extends Controller
{
    public function index()
    {
        $navs = [
            ['url' => route('backend.package.service.index'), 'text' => 'সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.top-service.index'), 'text' => 'টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        return view('backend.packages.top-service.index', compact('navs'));
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

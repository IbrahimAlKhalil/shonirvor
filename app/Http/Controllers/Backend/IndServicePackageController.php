<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use App\Models\PackageValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndServicePackageController extends Controller
{
    public function index()
    {

        $packages = Package::with('properties')->where('package_type_id', 1)->paginate(10);

        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.ind-top-service.index'), 'text' => 'ব্যাক্তিগত টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-top-service.index'), 'text' => 'প্রাতিষ্ঠানিক টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        return view('backend.packages.service.ind-service', compact('navs', 'packages'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $packageProperties = [
            'name' => 1,
            'description' => 2,
            'duration' => 3,
            'fee' => 4
        ];

        $package = new Package;
        $package->package_type_id = 1;
        $package->save();

        $data = [];
        foreach ($packageProperties as $key => $packagePropertyId) {
            array_push($data, [
                'package_property_id' => $packagePropertyId,
                'package_id' => $package->id,
                'value' => $request->post($key)
            ]);
        }

        DB::table('package_values')->insert($data);
        DB::commit();

        return back()->with('success', 'প্যাকেজ তৈরি করা হয়েছে');
    }

    public function update(Request $request)
    {
        DB::beginTransaction();

        foreach ($request->post('values') as $key => $value) {
            $valueModel = PackageValue::find($key);
            $valueModel->value = $value;
            $valueModel->save();
        }

        DB::commit();

        return back()->with('success', 'প্যাকেজ আপডেট হয়েছে!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return back()->with('success', 'প্যাকেজ ডিলিট হয়েছে!');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use App\Models\PackageValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class OrgTopServiceController extends Controller
{
    private $packageTypeId = 4;

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $packages = new Paginator(Package::with('properties')->where('package_type_id', $this->packageTypeId)->get()->sort(function ($a, $b) {
            $aProperties = $a->properties->groupBy('name');
            $bProperties = $b->properties->groupBy('name');

            return $aProperties['duration'][0]->value > $bProperties['duration'][0]->value;
        }), 10);

        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.ind-top-service.index'), 'text' => 'ব্যাক্তিগত টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-top-service.index'), 'text' => 'প্রাতিষ্ঠানিক টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'বিজ্ঞাপন প্যাকেজসমূহ']
        ];

        return view('backend.packages.top-service.org-service', compact('navs', 'packages'));
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
        $package->package_type_id = $this->packageTypeId;
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

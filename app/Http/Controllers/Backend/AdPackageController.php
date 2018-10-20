<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use App\Models\PackageValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdPackageController extends Controller
{
    private $packageTypeId = 6;
    private $packageProperties = [
        [
            'id' => 1,
            'name' => 'name'
        ],
        [
            'id' => 2,
            'name' => 'description'
        ],
        [
            'id' => 3,
            'name' => 'duration'
        ],
        [
            'id' => 4,
            'name' => 'fee'
        ]
    ];

    public function index()
    {
        $packages = Package::with('properties')->paginate(10);

        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.top-service.index'), 'text' => 'টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        return view('backend.packages.ad.index', compact('packages', 'navs'));
    }

    public function store(Request $request)
    {
        $packageValues = [];

        DB::beginTransaction();

        $package = new Package;
        $package->package_type_id = $this->packageTypeId;
        $package->save();

        foreach ($this->packageProperties as $property) {

            array_push($packageValues, [
                'package_id' => $package->id,
                'package_property_id' => $property['id'],
                'value' => $request->input($property['name'])
            ]);
        }

        DB::table('package_values')->insert($packageValues);

        DB::commit();

        return back()->with('success', 'প্যাকেজ তৈরি হয়েছে।');
    }

    public function update(Request $request)
    {
        foreach ($request->input('values') as $key => $value) {

            $packageValue = PackageValue::find($key);
            $packageValue->value = $value;
            $packageValue->save();

        }

        return back()->with('success', 'প্যাকেজ আপডেট হয়েছে।');

    }

    public function destroy(Package $package)
    {
        $package->delete();

        return back()->with('success', 'প্যাকেজ ডিলিট হয়েছে।');
    }
}

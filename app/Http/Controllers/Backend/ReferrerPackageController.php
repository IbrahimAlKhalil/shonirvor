<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use App\Models\PackageValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReferrerPackageController extends Controller
{
    private $packageTypeId = 5;
    private $packageProperties = [
        ['id' => 1, 'name' => 'name'],
        ['id' => 2, 'name' => 'description'],
        ['id' => 3, 'name' => 'duration'],
        ['id' => 5, 'name' => 'refer_target'],
        ['id' => 6, 'name' => 'refer_onetime_interest'],
        ['id' => 7, 'name' => 'refer_renew_interest'],
        ['id' => 8, 'name' => 'refer_fail_onetime_interest'],
        ['id' => 9, 'name' => 'refer_fail_renew_interest'],
        ['id' => 10, 'name' => 'is_default']
    ];

    public function index()
    {
        $packages = Package::with('properties')
            ->select('packages.id',
                'packages.package_type_id',
                'package_values.package_property_id',
                'package_values.value as is_default')
            ->join('package_values', function ($join) {
                $join->on('packages.id', 'package_values.package_id')
                    ->where('package_values.package_property_id', 10);
            })
            ->where('package_type_id', $this->packageTypeId)
            ->orderBy('is_default', 'desc')
            ->paginate(10);

        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.ind-top-service.index'), 'text' => 'ব্যাক্তিগত টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-top-service.index'), 'text' => 'প্রাতিষ্ঠানিক টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        return view('backend.packages.referrer.index', compact('packages', 'navs'));
    }

    public function store(Request $request)
    {
        $packageValues = [];

        DB::beginTransaction();

        $package = new Package;
        $package->package_type_id = $this->packageTypeId;
        $package->save();

        if ($request->input('is_default'))
            DB::table('package_values')->select(
                'package_values.id as id',
                'packages.id as package_id',
                'packages.package_type_id',
                'package_values.package_property_id',
                'package_values.value'
                )
                ->join('packages', 'package_values.package_id', 'packages.id')
                ->where([
                    ['package_property_id', 10],
                    ['package_type_id', 5],
                    ['value', 1]
                ])
                ->update([
                    'package_values.value' => null
                ]);

        foreach ($this->packageProperties as $property) {
            array_push($packageValues, [
                'package_id' => $package->id,
                'package_property_id' => $property['id'],
                'value' => $request->input($property['name']),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::table('package_values')->insert($packageValues);

        DB::commit();

        return back()->with('success', 'প্যাকেজ তৈরি হয়েছে।');
    }

    public function update(Request $request, Package $package)
    {
        DB::beginTransaction();

        if ($request->input('is_default'))
            DB::table('package_values')->select(
                'package_values.id as id',
                'packages.id as package_id',
                'packages.package_type_id',
                'package_values.package_property_id',
                'package_values.value'
                )
                ->join('packages', 'package_values.package_id', 'packages.id')
                ->where([
                    ['package_id', '!=', $package->id],
                    ['package_property_id', 10],
                    ['package_type_id', 5],
                    ['value', 1]
                ])
                ->update([
                    'package_values.value' => null
                ]);

        foreach ($this->packageProperties as $property) {
            $package->properties()
                ->where('package_property_id', $property['id'])
                ->update([
                    'value' => $request->input($property['name'])
                ]);
        }

        DB::commit();

        return back()->with('success', 'প্যাকেজ আপডেট হয়েছে।');

    }

    public function destroy(Package $package)
    {
        $package->delete();

        return back()->with('success', 'প্যাকেজ ডিলিট হয়েছে।');
    }
}
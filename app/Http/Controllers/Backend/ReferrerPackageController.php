<?php

namespace App\Http\Controllers\Backend;

use App\Models\Package;
use App\Models\PackageValue;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
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
        ['id' => 9, 'name' => 'refer_fail_renew_interest']
    ];

    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $packages = new Paginator(Package::with('properties')
            ->where('package_type_id', $this->packageTypeId)->get()->sort(function ($a, $b) {
                $aProperties = $a->properties->groupBy('name');
                $bProperties = $b->properties->groupBy('name');

                return $aProperties['duration'][0]->value > $bProperties['duration'][0]->value;
            }), 10);

        $deleted = new Paginator(Package::onlyTrashed()->with('properties')
            ->where('package_type_id', $this->packageTypeId)->get()->sort(function ($a, $b) {
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

        return view('backend.packages.referrer.index', compact('packages', 'deleted', 'navs'));
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
        if ($package->id == 1) return back()->with('error', 'ডিফল্ট প্যাকেজ ডিলিট করা যাবে না।');

        $package->delete();

        return back()->with('success', 'প্যাকেজ ডিলিট হয়েছে।');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'package' => 'required'
        ]);

        Package::withTrashed()->findOrFail($request->post('package'))->restore();

        return back()->with('success', 'প্যাকেজটি পুনরায় চালু হয়েছে।');
    }
}

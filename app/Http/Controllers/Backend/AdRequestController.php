<?php

namespace App\Http\Controllers\Backend;

use App\Models\Income;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $navs = [
            ['url' => route('backend.request.ad.index'), 'text' => 'বিজ্ঞাপন আবেদন সমূহ'],
            ['url' => route('backend.request.ad-edit.index'), 'text' => 'বিজ্ঞাপন এডিট আবেদন সমূহ'],
        ];

        $applications = Income::with([
            'incomeable' => function ($query) {
                $query->with('user');
            },
            'package.properties' => function ($query) {
                $query->where('name', 'name');
            }
        ])->where('incomeable_type', 'ad')->where('approved', 0)->paginate(15);

        return view('backend.request.ad.index', compact('applications', 'navs'));
    }

    public function show(Income $application)
    {
        $application->load([
            'incomeable',
            'paymentMethod',
            'package.properties' => function ($query) {
                $query->where('name', 'name');
            }
        ]);

        $ad = $application->incomeable;

        if ($application->approved) {
            abort(404);
        }

        $user = Auth::user();
        $properties = $application->package->properties->groupBy('name');

        return view('backend.request.ad.show', compact('ad', 'user', 'properties', 'application'));
    }

    public function update(Income $application)
    {
        $application->load([
            'package.properties',
            'incomeable'
        ]);
        DB::beginTransaction();

        $ad = $application->incomeable;
        $oldExpire = $ad->expire;
        $duration = $application->package->properties->groupBy('name')['duration'][0]->value;

        if ($oldExpire) {
            $ad->expire = $oldExpire->addDays($duration);
        } else {
            $ad->expire = now()->addDays($duration);
        }

        $ad->save();

        $application->approved = 1;
        $application->save();

        DB::commit();

        return redirect(route('backend.request.ad.index'))->with('success', 'বিজ্ঞাপন এডিট আবেদনটি গ্রহণ করা হয়েছে');
    }

    public function destroy(Income $application)
    {
        DB::beginTransaction();

        // TODO: Delete Image
        $application->delete();
        Storage::delete($application->image);

        DB::commit();

        return redirect(route('backend.request.ad.index'))->with('success', 'বিজ্ঞাপন এডিট আবেদনটি ডিলিট করা হয়েছে');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Ad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::select('id', 'image', 'url')->paginate(10);
        return view('backend.ad.index', compact('ads'));
    }

    public function store(Request $request)
    {
        $ad = new Ad();
        $ad->image = $request->file('image')->store('biggapon');
        $ad->url = $request->input('url');
        $ad->save();

        return back()->with('success', 'নতুন বিজ্ঞাপন তৈরি হয়েছে।');
    }

    public function update(Request $request, Ad $ad)
    {
        if ($request->hasFile('image')) {
            Storage::delete($ad->image);
            $ad->image = $request->file('image')->store('biggapon');
        }
        $ad->url = $request->input('url');
        $ad->save();

        return back()->with('success', 'বিজ্ঞাপন এডিট করা হয়েছে।');
    }

    public function destroy(Ad $ad)
    {
        Storage::delete($ad->image);
        $ad->delete();

        return back()->with('success', 'বিজ্ঞাপন  মুছে ফেলা হয়েছে।');
    }
}

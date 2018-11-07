<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\AdEdit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AdController extends Controller
{
    public function edit(Ad $ad)
    {
        $image = $ad->image;
        $url = $ad->url;
        $edit = $ad->edit;

        if ($edit) {
            $image = $edit->image;
            $url = $edit->url;
        }

        return view('frontend.ad.edit', compact('ad', 'image', 'url'));
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'url' => 'url|nullable',
            'image' => 'image|nullable'
        ]);

        $edit = $ad->edit;

        DB::beginTransaction();
        if ($edit == null) {
            $edit = new AdEdit;
        }

        if ($request->hasFile('image')) {
            $edit->image = $request->file('image')->store('user-photos/' . Auth::id());
        }
        $edit->ad_id = $ad->id;
        $edit->url = $request->url;
        $edit->save();
        DB::commit();

        return back()->with('success', 'আপনার অনুরোধটি গ্রহণ করা হয়েছে, অতিশীঘ্রয় এডমিন আপনার অনুরোধটি রিভিউ করবেন।');
    }
}

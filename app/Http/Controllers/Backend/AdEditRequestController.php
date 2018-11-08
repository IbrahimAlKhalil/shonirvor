<?php

namespace App\Http\Controllers\Backend;

use App\Models\AdEdit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdEditRequestController extends Controller
{

    public function index()
    {
        $navs = [
            ['url' => route('backend.request.ad.index'), 'text' => 'বিজ্ঞাপন আবেদন সমূহ'],
            ['url' => route('backend.request.ad-edit.index'), 'text' => 'বিজ্ঞাপন এডিট আবেদন সমূহ'],
        ];

        $adEdits = AdEdit::paginate(15);

        return view('backend.request.ad-edit.index', compact('navs', 'adEdits'));
    }

    public function show(AdEdit $adEdit)
    {
        $adEdit->load('ad.user');
        return view('backend.request.ad-edit.show', compact('adEdit'));
    }

    public function update(AdEdit $adEdit)
    {
        DB::beginTransaction();
        DB::table('ads')->where('id', $adEdit->ad_id)->update([
            'image' => $adEdit->image,
            'url' => $adEdit->url
        ]);

        $adEdit->delete();
        DB::commit();

        // TODO: Redirect to an appropriate page
        return redirect()->back()->with('success', 'বিজ্ঞাপন এডিট আবেদনটি গ্রহণ করা হয়েছে');
    }

    public function destroy(AdEdit $adEdit) {
        // TODO: Delete Image
        $adEdit->delete();
        return redirect()->back()->with('success', 'বিজ্ঞাপন এডিট আবেদনটি ডিলিট করা হয়েছে');
    }
}

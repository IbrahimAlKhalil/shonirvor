<?php

namespace App\Http\Controllers\Backend;

use App\Models\AdEdit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdEditRequestController extends Controller
{
    public function show(AdEdit $adEdit)
    {
        $adEdit->load('ad.user');
        return view('backend.request.ad-edit', compact('adEdit'));
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
        return response('done');
    }
}

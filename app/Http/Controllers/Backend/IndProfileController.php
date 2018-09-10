<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\IndService;
use Illuminate\Support\Facades\DB;

class IndProfileController extends Controller
{
    public function show(IndService $provider)
    {
        $visitor['today'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $provider->id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $provider->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $provider->id)->whereYear('created_at', date('Y'))->sum('how_much');

        return view('backend.ind-service-profile.show', compact('provider', 'visitor'));
    }
}

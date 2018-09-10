<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OrgService;
use Illuminate\Support\Facades\DB;

class OrgProfileController extends Controller
{
    public function show(OrgService $provider)
    {
        $visitor['today'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereYear('created_at', date('Y'))->sum('how_much');

        return view('backend.org-service-profile.show', compact('provider', 'visitor'));
    }
}

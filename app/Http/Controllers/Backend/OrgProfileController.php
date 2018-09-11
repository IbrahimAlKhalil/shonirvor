<?php

namespace App\Http\Controllers\Backend;

use App\Models\OrgService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrgProfileController extends Controller
{
    public function show(OrgService $provider)
    {
        $navs = $this->navs();
        $visitor['today'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('org_service_visitor_counts')->where('org_service_id', $provider->id)->whereYear('created_at', date('Y'))->sum('how_much');

        return view('backend.org-service-profile.show', compact('provider', 'visitor', 'navs'));
    }

    private function navs()
    {
        $indServices = Auth::user()->indService;
        $orgServices = Auth::user()->orgService;
        $navs = [];

        foreach ($indServices as $indService) {
            array_push($navs, ['url' => route('backend.ind-service.profile', $indService->id), 'text' => $indService->id]);
        }

        foreach ($orgServices as $orgService) {
            array_push($navs, ['url' => route('backend.org-service.profile', $orgService->id), 'text' => $orgService->id]);
        }

        return $navs;
    }
}

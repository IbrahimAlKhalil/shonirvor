<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OrgService;
use App\Http\Controllers\Controller;
use App\Models\OrgServiceVisitorCount;

class OrgServiceController extends Controller
{
    public function index()
    {
        $providers = OrgService::paginate(15);
        return view('frontend.org-service.index', compact('providers'));
    }

    public function show(OrgService $provider)
    {
        if ($count = OrgServiceVisitorCount::whereDate('created_at', date('Y-m-d'))->where('org_service_id', $provider->id)->first()) {
            $count->how_many++;
            $count->save();
        }
        else {
            $createNewRow = new OrgServiceVisitorCount();
            $createNewRow->org_service_id = $provider->id;
            $createNewRow->save();
        }

        return view('frontend.org-service.show', compact('provider'));
    }
}
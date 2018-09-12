<?php

namespace App\Http\Controllers\Frontend;

use App\Models\OrgService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OrgServiceController extends Controller
{
    public function index()
    {
        $providers = OrgService::paginate(15);
        return view('frontend.org-service.index', compact('providers'));
    }

    public function show(OrgService $provider)
    {
        if (DB::table('org_service_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('org_service_id', $provider->id)->exists()) {
            DB::table('org_service_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('org_service_id', $provider->id)->increment('how_much', 1, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('org_service_visitor_counts')->insert(
                ['org_service_id' => $provider->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );
        }

        if (Auth::check()) {
            $canFeedback = !$provider->feedbacks()->where('user_id', Auth::user()->getAuthIdentifier())->exists() && $provider->user->id != Auth::user()->getAuthIdentifier();
        } else {
            $canFeedback = false;
        }

        return view('frontend.org-service.show', compact('provider', 'canFeedback'));
    }
}
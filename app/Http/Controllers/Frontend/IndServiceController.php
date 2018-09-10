<?php

namespace App\Http\Controllers\Frontend;

use App\Models\IndService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class IndServiceController extends Controller
{
    public function index()
    {
        $providers = IndService::paginate(15);
        return view('frontend.ind-service.index', compact('providers'));
    }

    public function show(IndService $provider)
    {
        if (DB::table('ind_service_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_service_id', $provider->id)->exists()) {
            DB::table('ind_service_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_service_id', $provider->id)->increment('how_much', 1, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('ind_service_visitor_counts')->insert(
                ['ind_service_id' => $provider->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );
        }

        return view('frontend.ind-service.show', compact('provider'));
    }
}
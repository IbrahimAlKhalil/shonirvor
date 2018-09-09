<?php

namespace App\Http\Controllers\Frontend;

use App\Models\IndService;
use App\Http\Controllers\Controller;
use App\Models\IndServiceVisitorCount;

class IndServiceController extends Controller
{
    public function index()
    {
        $providers = IndService::paginate(15);
        return view('frontend.ind-service.index', compact('providers'));
    }

    public function show(IndService $provider)
    {
        if ($count = IndServiceVisitorCount::whereDate('created_at', date('Y-m-d'))->where('ind_service_id', $provider->id)->first()) {
            $count->how_many++;
            $count->save();
        }
        else {
            $createNewRow = new IndServiceVisitorCount();
            $createNewRow->ind_service_id = $provider->id;
            $createNewRow->save();
        }

        return view('frontend.ind-service.show', compact('provider'));
    }
}
<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndServiceController extends Controller
{
    public function index()
    {
        $providers = Ind::getOnly('approved')->paginate(15);
        return view('frontend.ind-service.index', compact('providers'));
    }

    public function show(Ind $provider)
    {
        $provider->load(['user', 'feedbacks']);

        if (DB::table('ind_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_id', $provider->id)->exists()) {
            DB::table('ind_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_id', $provider->id)->increment('how_much', 1, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('ind_visitor_counts')->insert(
                ['ind_id' => $provider->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );
        }

        if (Auth::check()) {
            $canFeedback = !$provider->feedbacks()->where('user_id', Auth::user()->getAuthIdentifier())->exists() && $provider->user->id != Auth::user()->getAuthIdentifier();
        } else {
            $canFeedback = false;
        }

        return view('frontend.ind-service.show', compact('provider', 'canFeedback'));
    }
}
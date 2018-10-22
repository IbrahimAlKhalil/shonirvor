<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrgServiceController extends Controller
{
    public function index()
    {
        $providers = Org::paginate(15);
        return view('frontend.org-service.index', compact('providers'));
    }

    public function show(Org $provider)
    {
        // Load relations
        $provider->load([
            'division',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategoryRates' => function ($query) {
                $query->onlyConfirmed();
            },
            'workImages',
            'feedbacks'
        ]);

        // Add avarage feedback
        $provider->feedbacks_avg = $provider->withFeedbacksAvg()->find($provider->id)->feedbacks_avg;

        // Feedback star color
        if ($provider->feedbacks_avg > 4.4) {

            $avgFeedbackColor = 'success';

        } elseif ($provider->feedbacks_avg > 3.4) {

            $avgFeedbackColor = 'primary';

        } elseif ($provider->feedbacks_avg > 2.4) {

            $avgFeedbackColor = 'info';

        } elseif ($provider->feedbacks_avg > 1.4) {

            $avgFeedbackColor = 'warning';

        } else {

            $avgFeedbackColor = 'danger';

        }

        // Visitor counter increment.
        if (DB::table('org_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('org_id', $provider->id)->exists()) {
            DB::table('org_visitor_counts')
                ->whereDate('created_at', date('Y-m-d'))
                ->where('org_id', $provider->id)
                ->increment('how_much', 1, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('org_visitor_counts')->insert([
                    'org_id' => $provider->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
        }

        // Here checking-- can requested user give feedback.
        if (Auth::check()) {
            $canFeedback = !$provider->feedbacks()->where('user_id', Auth::user()->getAuthIdentifier())->exists() && $provider->user->id != Auth::user()->getAuthIdentifier();
        } else {
            $canFeedback = false;
        }

        return view('frontend.org-service.show', compact('provider', 'avgFeedbackColor', 'canFeedback'));
    }
}
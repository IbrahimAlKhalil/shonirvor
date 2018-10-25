<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Org;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrgFeedback;

class OrgServiceController extends Controller
{
    public function show(Org $provider)
    {
        if ($provider->is_pending) abort(404, 'This service request is in pending.');

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

    public function feedbackStore(StoreOrgFeedback $request)
    {
        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->feedbackable_id = $request->post('feedbackable_id');
        $feedback->feedbackable_type = 'org';
        $feedback->star = $request->post('star');
        $feedback->say = $request->post('say');
        $feedback->save();

        return back()->with('success', 'Thank you for giving feedback.');
    }
}

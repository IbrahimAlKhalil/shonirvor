<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\StoreIndFeedback;
use App\Models\Feedback;
use App\Models\Ind;
use App\Models\Org;
use App\Models\Slug;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function show($slug)
    {
        $slug = Slug::where('name', $slug)->select('sluggable_type', 'sluggable_id')->first();
        if (!$slug) abort(404);

        return $this->{$slug->sluggable_type}($slug);
    }

    public function ind($slug)
    {
        $provider = Ind::with([
            'user',
            'division',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategories',
            'subCategories.workMethods' => function ($query) use ($slug) {
                $query->where('ind_id', $slug->sluggable_id);
            },
            'workImages',
            'feedbacks'
        ])->findOrFail($slug->sluggable_id);

        if (is_null($provider->expire)) abort(404, 'This service request is in pending.');

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
        if (DB::table('ind_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_id', $provider->id)->exists()) {
            DB::table('ind_visitor_counts')->whereDate('created_at', date('Y-m-d'))->where('ind_id', $provider->id)->increment('how_much', 1, ['updated_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('ind_visitor_counts')->insert(
                ['ind_id' => $provider->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
            );
        }

        // Here checking-- can requested user give feedback.
        if (Auth::check()) {
            $canFeedback = !$provider->feedbacks()->where('user_id', Auth::user()->getAuthIdentifier())->exists() && $provider->user->id != Auth::user()->getAuthIdentifier();
        } else {
            $canFeedback = false;
        }

        return view('frontend.ind-service', compact('provider', 'avgFeedbackColor', 'canFeedback'));
    }

    public function org($slug)
    {
        $provider = Org::with([
            'division',
            'district',
            'thana',
            'union',
            'village',
            'category',
            'subCategoryRates' => function ($query) {
                $query->onlyConfirmed();
            },
            'additionalPrices',
            'workImages',
            'feedbacks'
        ])->findOrFail($slug->sluggable_id);

        if (is_null($provider->expire)) abort(404, 'This service request is in pending.');

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

        return view('frontend.org-service', compact('provider', 'avgFeedbackColor', 'canFeedback'));
    }

    public function feedbackStore(StoreIndFeedback $request)
    {
        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->feedbackable_id = $request->post('feedbackable_id');
        $feedback->feedbackable_type = $request->post('type');
        $feedback->star = $request->post('star');
        $feedback->say = $request->post('say');
        $feedback->save();

        return back()->with('success', 'মতামত দেওয়ার জন্য ধন্যবাদ ।');
    }
}

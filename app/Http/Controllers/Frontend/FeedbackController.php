<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrgFeedback;
use App\Http\Requests\StoreIndFeedback;

class FeedbackController extends Controller
{
    public function indStore(StoreIndFeedback $request)
    {
        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->feedbackable_id = $request->post('feedbackable_id');
        $feedback->feedbackable_type = 'ind';
        $feedback->star = $request->post('star');
        $feedback->say = $request->post('say');
        $feedback->save();

        return back()->with('success', 'Thank you for giving feedback.');
    }

    public function orgStore(StoreOrgFeedback $request)
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
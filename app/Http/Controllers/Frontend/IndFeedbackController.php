<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Feedback;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreIndFeedback;

class IndFeedbackController extends Controller
{
    public function store(StoreIndFeedback $request)
    {
        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->feedbackable_id = $request->post('feedbackable_id');
        $feedback->feedbackable_type = 'ind';
        $feedback->say = $request->post('say');
        $feedback->save();

        return back()->with('success', 'Thank you for giving feedback.');
    }
}
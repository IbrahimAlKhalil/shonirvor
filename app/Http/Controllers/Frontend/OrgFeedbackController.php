<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Feedback;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreOrgFeedback;

class OrgFeedbackController extends Controller
{
    public function store(StoreOrgFeedback $request)
    {
        DB::beginTransaction();

        $feedback = new Feedback();
        $feedback->user_id = Auth::id();
        $feedback->feedbackable_id = $request->post('feedbackable_id');
        $feedback->feedbackable_type = 'org';
        $feedback->say = $request->post('say');
        $feedback->save();

        DB::commit();

        return back()->with('success', 'Thank you for giving feedback.');
    }
}

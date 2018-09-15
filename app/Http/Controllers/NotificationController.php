<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\AdminToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function send(User $user, Request $request)
    {
        $user->notify(new AdminToUser($request->input('notification')));
        return back()->with('success', 'নোটিফিকেশন পাঠানো হয়েছে।');
    }

    public function show()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('frontend.notification', compact('notifications'));
    }
}
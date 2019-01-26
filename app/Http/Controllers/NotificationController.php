<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function send(User $user, Request $request)
    {
        $user->notify(new Notification($request->input('message')));
        return back()->with('success', 'নোটিফিকেশন পাঠানো হয়েছে।');
    }
}
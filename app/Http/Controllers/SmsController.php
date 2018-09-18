<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send(User $user, Request $request)
    {
        $user->notify(new Sms($request->input('notification')));
        return back()->with('success', 'মেসেজ পাঠানো হয়েছে।');
    }
}
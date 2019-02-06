<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function send(User $user, Request $request)
    {
        $response = sms($user->mobile, urldecode($request->input('message')));

        if ($response['success']) {
            return back()->with('success', $response['status']);
        } else {
            return back()->with('error', $response['status']);
        }
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Support\MessageBag;
use App\Http\Requests\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PasswordResetSendCode;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRequestForm()
    {
        return view('auth.passwords.request');
    }

    public function sendCode(PasswordResetSendCode $request)
    {
        $resetToken = rand(100000, 999999);
        $user = User::where('mobile', $request->input('mobile'))
                    ->withoutGlobalScope('validate')->first();
        $user->reset_token = $resetToken;
        $user->save();

        $user->notify(new Sms('Password reset code of AreaSheba: '.$resetToken));

        return redirect()
            ->route('password.reset', $user->id)
            ->with('message', 'আপনার '.en2bnNumber($user->mobile).' এই নাম্বারে একটি কোড পাঠানো হয়েছে।');
    }

    public function showResetForm($user)
    {
        User::withoutGlobalScope('validate')
            ->whereNotNull('reset_token')
            ->findOrFail($user);

        return view('auth.passwords.reset');
    }

    public function reset(PasswordReset $request, $user)
    {
        $user = User::withoutGlobalScope('validate')
            ->whereNotNull('reset_token')
            ->findOrFail($user);

        if ($user->reset_token == $request->input('code')) {

            $user->password = bcrypt($request->input('password'));
            $user->reset_token = null;
            $user->save();

            Auth::loginUsingId($user->id);

            return redirect()->route('home');
        } else {
            $errors = new MessageBag;
            $errors->add('code', 'SMS কোড ভুল হয়েছে।');

            return back()->withErrors($errors)->withInput();
        }
    }
}

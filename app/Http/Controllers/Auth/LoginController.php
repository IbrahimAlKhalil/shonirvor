<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        login as protected traitlogin;
    }

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|digits:11||exists:users',
            'password' => 'required|string'
        ];

        $messages = [
            $this->username().'.required' => 'মোবাইল নাম্বার দিতে হবে।',
            $this->username().'.digits' => 'মোবাইল নাম্বার অবশ্যই ১১ সংখ্যার হতে হবে।',
            $this->username().'.exists' => 'আপনি ভুল মোবাইল নাম্বার দিয়েছেন। এই নাম্বারে কোন একাউন্ট নেই।'
        ];

        $this->validate($request, $rules, $messages);
    }

    public function login(Request $request)
    {
        if (User::withoutGlobalScope('validate')
            ->where('mobile', $request->input('mobile'))
            ->whereNotNull('verification_token')->exists()) {

            if (Hash::check($request->input('password'), User::withoutGlobalScope('validate')
                ->where('mobile', $request->input('mobile'))
                ->whereNotNull('verification_token')->first()->password)) {

                $user = User::withoutGlobalScope('validate')
                    ->where('mobile', $request->input('mobile'))
                    ->first();

                $user->notify(new Sms('Verification code of AreaSheba: '. $user->verification_token));

                return redirect()->route('verification', $user->id);
            }
        }

        return $this->traitlogin($request);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->reset_token) {
            $user->reset_token = null;
            $user->save();
        }
    }

    public function username()
    {
        return 'mobile';
    }
}

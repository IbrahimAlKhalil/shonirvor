<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|digits:11',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        if (User::withoutGlobalScope('validate')->where('mobile', $request->input('mobile'))->whereNotNull('verification_token')->exists()) {
            $user = User::withoutGlobalScope('validate')
                ->where('mobile', $request->input('mobile'))
                ->first();

            return redirect()->route('verification', $user->id);
        }

        return $this->traitlogin($request);
    }

    public function username()
    {
        return 'mobile';
    }
}

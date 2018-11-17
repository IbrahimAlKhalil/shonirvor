<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users|digits:11',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'verification_token' => rand(100000, 999999),
            'password' => bcrypt($data['password'])
        ]);
    }

    protected function registered(Request $request, $user)
    {
        Auth::logout();

        $user->notify(new Sms('Verification code of AreaSheba: '. $user->verification_token));

        $this->redirectTo = route('verification', $user->id);
    }

    public function verificationForm($user)
    {
        $user = User::withoutGlobalScope('validate')->findOrFail($user);

        if ($user->verification_token == null) {
            abort(404, 'This user is already varified.');
        }

        return view('auth.verification', compact('user'));
    }

    public function verification(Request $request, $user)
    {
        $user = User::withoutGlobalScope('validate')->findOrFail($user);

        if ($user->verification_token == $request->verification) {
            $user->verification_token = null;
            $user->save();

            Auth::loginUsingId($user->id);

            return redirect()->route('home');
        } else {
            $errors = new MessageBag;
            $errors->add('verification', 'ভেরিফিকেশন কোড ভুল হয়েছে।');

            return back()->withErrors($errors);
        }
    }
}

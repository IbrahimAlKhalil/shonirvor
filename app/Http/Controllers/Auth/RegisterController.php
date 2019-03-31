<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use App\Http\Requests\Verification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
//        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'mobile' => 'required|unique:users|digits:11',
            'password' => 'required|string|min:6|confirmed'
        ];

        $messages = [
            'name.required' => 'নাম দিতে হবে।',
            'name.max' => 'সর্বোচ্চ ২৫৫ শব্দের নাম দেওয়া যাবে।',
            'mobile.required' => 'মোবাইল নাম্বার দিতে হবে।',
            'mobile.digits' => 'মোবাইল নাম্বার অবশ্যই ১১ সংখ্যার হতে হবে।',
            'mobile.unique' => 'এই নাম্বার দিয়ে আগেই একটি একাউন্ট খোলা হয়েছে।',
            'password.required' => 'পাসওয়ার্ড দিতে হবে।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ শব্দের হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড মিলে নাই।'
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function create(array $data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->mobile = $data['mobile'];
        $user->verification_token = rand(100000, 999999);
        $user->password = bcrypt($data['password']);
        $user->save();

        return $user;
    }

    public function register(Request $request)
    {
        $data = $request->all();

        $this->validator($data)->validate();
        $user = $this->create($data);
        $id = $user->id;
        $token = $user->verification_token;

        $user->notify(new Sms("The verification code from AreaSheba is: $token"));

        return redirect(route('verification', $id));
    }

    public function verificationForm($user)
    {
        $user = User::withoutGlobalScope('validate')
            ->whereNotNull('verification_token')
            ->findOrFail($user);

        return view('auth.verification', compact('user'));
    }

    public function verification(Verification $request, $user)
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

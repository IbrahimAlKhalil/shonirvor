<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\PaymentReceiveMethod;
use App\Models\User;
use App\Models\UserPaymentReceiveMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::with('referPackage.package.properties', 'references', 'paymentReceiveMethod')
            ->find(Auth::id());

        $references = $user->references->filter(function ($value) {
            return $value->service->expire != null;
        });

        $totalEarn = userTotalEarn($user);
        $totalPaymentGet = $user->earns()->where('expense_type_id', 1)->sum('amount');
        $payable = $totalEarn - $totalPaymentGet;

        $referPackage = userReferrerPackage($user)->properties->groupBy('name');

        $notifications = Auth::user()->notifications()->take(10)->get();

        return view('frontend.profile.index', compact('user', 'totalEarn', 'payable', 'referPackage', 'references', 'notifications'));
    }

    public function edit(User $profile)
    {
        return view('frontend.profile.edit', compact('profile'));
    }

    public function update(Request $request, User $profile)
    {
        $profile->name = $request->post('name');
        $profile->mobile = $request->post('mobile');
        $profile->email = $request->post('email');
        $profile->address = $request->post('address');
        if ($request->hasFile('photo')) {
            Storage::delete($profile->photo);
            $profile->photo = $request->file('photo')->store('user-photos/' . Auth::id());
        }
        $profile->save();

        return redirect(route('profile.index'))->with('success', 'Profile Information edited successfully.');
    }

    public function paymentReceiveMethod(PaymentReceiveMethod $request, User $profile)
    {
        UserPaymentReceiveMethod::updateOrCreate(
            ['user_id' => $profile->id],
            [
                'type' => $request->input('type'),
                'number' => $request->input('number')
            ]
        );

        return back()->with('success', 'ইনকাম গ্রহণের মাধ্যম পরিবর্তিত হয়েছে।');
    }
}
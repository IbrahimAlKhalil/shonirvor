<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\UpdateProfile;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:profile.update,profile', ['only' => ['update', 'edit', 'showMobileVerificationPage', 'saveMobileEdit']]);
    }

    public function index()
    {
        $profile = User::find(Auth::user()->id);
        return view('frontend.profile.index', compact('profile'));
    }

    public function edit(User $profile)
    {
        return view('frontend.profile.edit', compact('profile'));
    }

    public function update(UpdateProfile $request, User $profile)
    {
        $profile->name = $request->post('name');
        $profile->email = $request->post('email');
        $profile->address = $request->post('address');
        if ($request->filled('password')) {
            $profile->password = bcrypt($request->post('password'));
        }

        if ($request->hasFile('photo')) {
            Storage::delete($profile->photo);
            $profile->photo = $request->file('photo')->store('user-photos/' . Auth::id());
        }

        if ($request->post('mobile') != $profile->mobile) {
            $verificationCode = rand(100000, 999999);
            DB::table('user_mobile_edits')->updateOrInsert(
                ['user_id' => $profile->id],
                [
                    'mobile' => $request->post('mobile'),
                    'verification_code' => $verificationCode
                ]
            );

            // TODO: Uncomment the line below
//            sms($profile->mobile, 'Your mobile number verification code from AreaSheba code is ' . $verificationCode);
        }

        $profile->save();

        return redirect(route('profile.mobile-verification.show', $profile->id));
    }


    public function showMobileVerificationPage(User $profile)
    {
        $edit = DB::table('user_mobile_edits')->where('user_id', $profile->id)->first();
        if (!$edit) {
            abort(404);
        }
        return view('frontend.profile.verification', compact('profile', 'edit'));
    }

    public function saveMobileEdit(User $profile, Request $request)
    {
        $request->validate([
            'verification' => 'required'
        ]);

        $edit = $edit = DB::table('user_mobile_edits')->where('user_id', $profile->id)->first();
        if (!$edit) {
            abort(404);
        }

        if ($edit->verification_code != $request->post('verification')) {
            return back()->with('verification-mismatch', 'আপনি ভুল কোড দিয়েছেন');
        }

        DB::beginTransaction();
        $profile->mobile = $edit->mobile;
        $profile->save();
        DB::table('user_mobile_edits')->where('id', $edit->id)->delete();

        DB::commit();

        return redirect(route('profile.index'))->with('success', 'আপনার মোবাইল নাম্বারটি সফলভাবে পরিবর্তন হয়েছে');
    }
}
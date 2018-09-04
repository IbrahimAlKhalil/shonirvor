<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = User::find(Auth::user()->id);
        return view('frontend.profile.index', compact('profile'));
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
}
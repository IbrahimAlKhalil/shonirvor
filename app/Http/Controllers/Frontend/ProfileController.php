<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        dd($request->all());
    }
}
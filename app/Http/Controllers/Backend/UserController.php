<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\UserReferPackage;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $defaultReferPackage;

    public function __construct()
    {
        $this->defaultReferPackage = Package::where('package_type_id', 5)->first();
    }

    public function index()
    {
        $users = User::select('id', 'name', 'mobile')->paginate(20);
        return view('backend.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $referPackages = Package::with('properties')->where('package_type_id', 5)->get();

        if ($user->referPackage()->exists()) {
            $userReferPackageId = $user->referPackage->package_id;
        } else {
            $userReferPackageId = $this->defaultReferPackage->id;
        }

        return view('backend.users.show', compact('user', 'referPackages', 'userReferPackageId'));
    }

    public function updateReferPackage(User $user, Request $request)
    {
        if ($user->referPackage()->exists()) {

            if ($request->input('refer-id') == $this->defaultReferPackage->id) {

                $user->referPackage->delete();
                return back()->with('success', 'রেফার প্যাকেজ পরিবর্তিত হয়েছে।');

            } else {

                $referPackage = $user->referPackage;

            }

        } else {

            $referPackage = new UserReferPackage;
            $referPackage->user_id = $user->id;

        }

        $referPackage->package_id = $request->input('refer-id');
        $referPackage->save();

        return back()->with('success', 'রেফার প্যাকেজ পরিবর্তিত হয়েছে।');
    }
}

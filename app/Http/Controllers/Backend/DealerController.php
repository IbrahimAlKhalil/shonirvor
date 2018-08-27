<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DealerController extends Controller
{
    public function __construct()
    {
//        $this->middleware('role:superadmin');
    }


    public function index()
    {
        return view('backend.dealer.index');
    }


    public function create()
    {
        return view('backend.dealer.create');
    }


    public function store(Request $request)
    {
        $dealer = new User();
        $dealer->name = $request->post('name');
        $dealer->mobile = $request->post('mobile');
        $dealer->email = $request->post('email');
        $dealer->age = $request->post('age');
        $dealer->qualification = $request->post('qualification');
        $dealer->address = $request->post('address');
        $dealer->save();

        $dealer->photo = $request->file('photo')->store('users/'.$dealer->id);
        $dealer->save();

        return redirect(route('dealer.index'))->with('success', 'Done!!');
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        //
    }


    public function update(Request $request, User $user)
    {
        //
    }


    public function destroy(User $user)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDealer;
use App\Http\Controllers\Controller;

class DealerController extends Controller
{
    public function __construct()
    {
//        $this->middleware('role:superadmin');
    }


    public function index()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'dealer');
        })->paginate(15);
        return view('backend.dealer.index', compact('users'));
    }


    public function create()
    {
        return view('backend.dealer.create');
    }


    public function store(StoreDealer $request)
    {
        $dealer = new User();
        $dealer->name = $request->post('name');
        $dealer->mobile = $request->post('mobile');
        $dealer->email = $request->post('email');
        $dealer->nid = $request->post('nid');
        $dealer->age = $request->post('age');
        $dealer->qualification = $request->post('qualification');
        $dealer->address = $request->post('address');
        $dealer->password = bcrypt($request->post('address'));
        $dealer->save();

        $dealer->roles()->attach(2);

        if ($request->hasFile('photo')) {
            $dealer->photo = $request->file('photo')->store('user-photos/' . $dealer->id);
            $dealer->save();
        }

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $paper = new UserDocument();
                $paper->user_id = $dealer->id;
                $paper->document = $document->store('users-documents/'.$dealer->id);
                $paper->save();
            }
        }

        return redirect(route('dealer.index'))->with('success', 'Dealer Created!!');
    }


    public function show($id)
    {
        $user = User::with(['dealer', 'dealer.documents'])->find($id);
        return view('backend.dealer.show', compact('user'));
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

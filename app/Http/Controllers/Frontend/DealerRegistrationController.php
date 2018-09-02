<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\PendingDealer;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\PendingDealerDocument;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDealerRegistration;

class DealerRegistrationController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('dealer')) {
            return redirect(route('backend.home'));
        }
        if (PendingDealer::where('user_id', Auth::id())->first()) {
            return redirect(route('dealer-registration.edit', Auth::id()));
        }

        $user = User::find(Auth::id());
        return view('frontend.registration.dealer.index', compact('user'));
    }

    public function store(StoreDealerRegistration $request)
    {
        $dealerInfo = new PendingDealer();
        $dealerInfo->user_id = Auth::id();
        $dealerInfo->mobile = $request->post('mobile');
        $dealerInfo->email = $request->post('email');
        $dealerInfo->category = $request->post('category');
        $dealerInfo->district = $request->post('district');
        $dealerInfo->thana = $request->post('thana');
        $dealerInfo->union = $request->post('union');
        $dealerInfo->no_area = $request->post('no_area');
        $dealerInfo->address = $request->post('address');
        $dealerInfo->save();

        $generalInfo = User::find(Auth::id());
        $generalInfo->age = $request->post('age');
        $generalInfo->nid = $request->post('nid');
        $generalInfo->qualification = $request->post('qualification');
        if ($request->hasFile('photo')) {
            Storage::delete($generalInfo->photo);
            $generalInfo->photo = $request->file('photo')->store('user-photos/' . Auth::id());
        }
        $generalInfo->save();

        if ($request->hasFile('documents')) {
            $documents = [];
            foreach ($request->documents as $document) {
                array_push($documents, [
                    'pending_dealer_id' => $dealerInfo->id,
                    'path' => $document->store('pending-dealer-documents')
                ]);
            }
            PendingDealerDocument::insert($documents);
        }

        return back()->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');
    }

    public function edit($id)
    {
        if (Auth::id() != $id) {
            return abort(403);
        }
        if (!PendingDealer::where('user_id', Auth::id())->first()) {
            return redirect(route('dealer-registration.index'));
        }

        return view('frontend.registration.dealer.edit');
    }

    public function update()
    {
        //
    }

    public function instruction()
    {
        return view('frontend.registration.dealer.instruction');
    }
}

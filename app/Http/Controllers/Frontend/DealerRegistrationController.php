<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\PendingDealer;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\PendingDealerDocument;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreDealerRegistration;
use App\Http\Requests\UpdateDealerRegistration;

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

        return redirect(route('dealer-registration.edit', Auth::id()))->with('success', 'Thanks! we will review your request as soon as possible, so stay tuned!');
    }

    public function edit($id)
    {
        if (Auth::id() != $id) {
            return abort(403);
        }
        if (!PendingDealer::where('user_id', Auth::id())->first()) {
            return redirect(route('dealer-registration.index'));
        }

        $user = User::with('pendingDealer')->find($id);
        return view('frontend.registration.dealer.edit', compact('user'));
    }

    public function update(UpdateDealerRegistration $request, $id)
    {
        $user = User::with(['pendingDealer', 'pendingDealer.documents'])->find($id);

        $user->age = $request->post('age');
        $user->qualification = $request->post('qualification');
        $user->nid = $request->post('nid');
        $user->save();

        $user->pendingDealer->mobile = $request->post('mobile');
        $user->pendingDealer->email = $request->post('email');
        $user->pendingDealer->category = $request->post('category');
        $user->pendingDealer->category = $request->post('category');
        $user->pendingDealer->district = $request->post('district');
        $user->pendingDealer->thana = $request->post('thana');
        $user->pendingDealer->union = $request->post('union');
        $user->pendingDealer->no_area = $request->post('no_area');
        $user->pendingDealer->address = $request->post('address');
        $user->pendingDealer->save();

        if ($request->hasFile('documents')) {
            foreach ($user->pendingDealer->documents as $document) {
                Storage::delete($document->path);
                $document->delete();
            }
            foreach ($request->documents as $document) {
                $pendingDealerDocument = new PendingDealerDocument();
                $pendingDealerDocument->pending_dealer_id = $user->pendingDealer->id;
                $pendingDealerDocument->path = $document->store('pending-dealer-documents');
                $pendingDealerDocument->save();
            }
        }

        return back()->with('success', 'Data updated successfully!');
    }

    public function instruction()
    {
        return view('frontend.registration.dealer.instruction');
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\DealerRegistration;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DealerRequestController extends Controller
{

    public function index()
    {
        $dealerRequests = DealerRegistration::paginate(15);

        return view('backend.dealer-request.index', compact('dealerRequests'));
    }


    public function show($id)
    {
        $dealerRequest = DealerRegistration::find($id);

        return view('backend.dealer-request.show', compact('dealerRequest'));
    }


    public function approve($id)
    {
        $dealerRegistration = DealerRegistration::find($id);
        $dealerDocuments = $dealerRegistration->documents;


        $newUser = new User();
        $newUser->name = $dealerRegistration->name;
        $newUser->mobile = $dealerRegistration->mobile;
        $newUser->email = $dealerRegistration->email;
        $newUser->nid = $dealerRegistration->nid;
        $newUser->age = $dealerRegistration->age;
        $newUser->qualification = $dealerRegistration->qualification;
        $newUser->address = $dealerRegistration->address;
        $newUser->photo = $dealerRegistration->photo;
        $newUser->password = $dealerRegistration->password;
        $newUser->save();

        $newUser->roles()->attach(2);

        $newDocuments = [];

        foreach ($dealerDocuments as $document) {
            $filename = basename(asset('storage/' . $document->document));

            Storage::move($document->document, 'users-documents/' . $filename);

            array_push($newDocuments, [
                'document' => 'users-documents/' . $newUser->id . '/' . $filename,
                'user_id' => $newUser->id
            ]);
        }

        UserDocument::insert($newDocuments);

        $dealerRegistration->delete();

        return redirect('dealer-request')->with('success', 'Dealer Request approved successfully!');

    }


    public function reject($id)
    {

        foreach (DealerRegistration::find($id)->documents as $document) {
            Storage::delete($document->document);
        }

        DealerRegistration::find($id)->delete();

        return redirect(route('dealer-request.index'))->with('success', 'Dealer Request rejected successfully!');
    }
}

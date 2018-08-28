<?php

namespace App\Http\Controllers\Backend;

use App\Models\DealerRegistration;
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
        dd(DealerRegistration::find($id));
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

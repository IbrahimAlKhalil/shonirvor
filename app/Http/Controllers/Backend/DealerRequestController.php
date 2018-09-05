<?php

namespace App\Http\Controllers\Backend;

use App\Models\Dealer;
use App\Models\DealerDocument;
use App\Models\PendingDealer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DealerRequestController extends Controller
{

    public function index()
    {
        $dealerRequests = PendingDealer::paginate(15);
        return view('backend.dealer-request.index', compact('dealerRequests'));
    }

    public function show(PendingDealer $dealerRequest)
    {
        return view('backend.dealer-request.show', compact('dealerRequest'));
    }

    public function approve($id)
    {
        $pendingDealer = PendingDealer::find($id);
        $pendingDealerDocuments = $pendingDealer->documents;

        $dealer = new Dealer();
        $dealer->user_id = $pendingDealer->user_id;
        $dealer->mobile = $pendingDealer->mobile;
        $dealer->email = $pendingDealer->email;
        $dealer->district = $pendingDealer->district;
        $dealer->thana = $pendingDealer->thana;
        $dealer->union = $pendingDealer->union;
        $dealer->address = $pendingDealer->address;
        $dealer->save();

        $dealer->user->attachRole(2);

        $movingDocuments = [];
        foreach ($pendingDealerDocuments as $document) {
            $filename = basename(asset('storage/' . $document->path));

            Storage::move($document->path, 'dealer-documents/' . $dealer->id . '/' . $filename);

            array_push($movingDocuments, [
                'dealer_id' => $dealer->id,
                'path' => 'dealer-documents/' . $dealer->id . '/' . $filename
            ]);
        }

        DealerDocument::insert($movingDocuments);

        $pendingDealer->delete();

        return redirect(route('dealer.index'))->with('success', 'Dealer Request approved successfully!');
    }


    public function reject($id)
    {
        foreach (PendingDealer::find($id)->documents as $document) {
            Storage::delete($document->document);
        }

        PendingDealer::find($id)->delete();

        return redirect(route('dealer-request.index'))->with('success', 'Dealer Request rejected successfully!');
    }
}

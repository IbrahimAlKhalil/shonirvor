<?php

namespace App\Http\Controllers\Backend;

use App\Models\IndService;
use App\Models\IndServiceDoc;
use App\Models\IndServiceImage;
use App\Models\PendingIndService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class IndServiceRequestController extends Controller
{

    public function index()
    {
        $serviceRequests = PendingIndService::paginate(15);

        return view('backend.ind-service-request.index', compact('serviceRequests'));
    }


    public function show($id)
    {
        $serviceRequest = PendingIndService::find($id);

        return view('backend.ind-service-request.show', compact('serviceRequest'));
    }

    public function store(Request $request)
    {
        $pendingService = PendingIndService::find($request->post('id'));
        $pendingDocs = $pendingService->docs;
        $pendingImages = $pendingService->images;

        $service = new IndService;
        $service->user_id = $pendingService->user_id;
        $service->mobile = $pendingService->mobile;
        $service->email = $pendingService->email;
        $service->latitude = $pendingService->latitude;
        $service->longitude = $pendingService->longitude;
        $service->service = $pendingService->service;
        $service->address = $pendingService->address;

        $service->save();


        $documents = [];
        $images = [];

        foreach ($pendingDocs as $pendingDoc) {
            $filename = basename(asset('storage/' . $pendingDoc->doc));

            Storage::move($pendingDoc->doc, 'ind-service-docs/' . $service->id . '/' . $filename);

            array_push($documents, [
                'doc' => 'ind-service-docs/' . $service->id . '/' . $filename,
                'ind_service_id' => $service->id
            ]);
        }

        foreach ($pendingImages as $pendingImage) {
            $filename = basename(asset('storage/' . $pendingImage->image));

            Storage::move($pendingImage->image, 'ind-service-images/' . $service->id . '/' . $filename);

            array_push($images, [
                'image' => 'ind-service-images/' . $service->id . '/' . $filename,
                'ind_service_id' => $service->id
            ]);
        }

        IndServiceDoc::insert($documents);
        IndServiceImage::insert($images);

        $pendingService->delete();


        return redirect(route('ind-service-request.index'))->with('success', 'Service Provider approved successfully!');

    }

    public function destroy($id)
    {
        $pendingService = PendingIndService::find($id);

        foreach ($pendingService->docs as $doc) {
            Storage::delete($doc->doc);
        }
        foreach ($pendingService->images as $image) {
            Storage::delete($image->image);
        }
        PendingIndService::find($id)->delete();

        return redirect(route('ind-service-request.index'))->with('success', 'Service Provider request rejected successfully!');
    }

}

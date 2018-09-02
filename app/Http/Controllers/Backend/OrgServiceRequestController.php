<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\OrgService;
use App\Models\OrgServiceDoc;
use App\Models\OrgServiceImage;
use App\Models\PendingOrgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrgServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = PendingOrgService::paginate(15);

        return view('backend.org-service-request.index', compact('serviceRequests'));
    }


    public function show($id)
    {
        $serviceRequest = PendingOrgService::find($id);

        return view('backend.org-service-request.show', compact('serviceRequest'));
    }

    public function store(Request $request)
    {

        $pendingService = PendingOrgService::find($request->post('id'));
        $pendingDocs = $pendingService->docs;
        $pendingImages = $pendingService->images;

        $service = new OrgService;
        $service->user_id = $pendingService->user_id;
        $service->org_name = $pendingService->org_name;
        $service->mobile = $pendingService->mobile;
        $service->description = $pendingService->description;
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

            Storage::move($pendingDoc->doc, 'org-service-docs/' . $service->id . '/' . $filename);

            array_push($documents, [
                'doc' => 'org-service-docs/' . $service->id . '/' . $filename,
                'org_service_id' => $service->id
            ]);
        }

        foreach ($pendingImages as $pendingImage) {
            $filename = basename(asset('storage/' . $pendingImage->image));

            Storage::move($pendingImage->image, 'org-service-images/' . $service->id . '/' . $filename);

            array_push($images, [
                'image' => 'org-service-images/' . $service->id . '/' . $filename,
                'org_service_id' => $service->id
            ]);
        }

        OrgServiceDoc::insert($documents);
        OrgServiceImage::insert($images);

        $pendingService->delete();


        return redirect(route('org-service-request.index'))->with('success', 'Service Provider approved successfully!');

    }

    public function destroy($id)
    {
        $pendingService = PendingOrgService::find($id);

        foreach ($pendingService->docs as $doc) {
            Storage::delete($doc->doc);
        }
        foreach ($pendingService->images as $image) {
            Storage::delete($image->image);
        }
        PendingOrgService::find($id)->delete();

        return redirect(route('org-service-request.index'))->with('success', 'Service Provider request rejected successfully!');
    }
}

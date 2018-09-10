<?php

namespace App\Http\Controllers\Backend;

use App\Models\IndService;
use App\Models\IndServiceDoc;
use App\Models\IndServiceImage;
use App\Models\PendingIndService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class IndServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = PendingIndService::paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show($id)
    {
        $serviceRequest = PendingIndService::find($id);
        $navs = $this->navs();
        return view('backend.ind-service-request.show', compact('serviceRequest', 'navs'));
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

        $user = User::find($service->user_id);

        if(!$user->hasRole('ind-service')) {
            $user->roles()->attach(3);
        }



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

        return redirect(route('individual-service-request.index'))->with('success', 'Service Provider approved successfully!');
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

        return redirect(route('individual-service-request.index'))->with('success', 'Service Provider request rejected successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-service.index'), 'text' => 'All Service Provider'],
            ['url' => route('individual-service-request.index'), 'text' => 'Service Requests'],
            ['url' => route('individual-service.disabled'), 'text' => 'Disabled Service Provider'],
        ];
    }
}
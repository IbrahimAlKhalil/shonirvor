<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreApproveIndService;
use App\Models\IndCategory;
use App\Models\IndService;
use App\Models\IndServiceDoc;
use App\Models\IndServiceImage;
use App\Models\IndSubCategory;
use App\Models\PendingIndService;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndServiceRequestController extends Controller
{
    public function index()
    {
        $serviceRequests = PendingIndService::orderBy('updated_at', 'DSC')->paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service-request.index', compact('serviceRequests', 'navs'));
    }

    public function show($id)
    {
        $serviceRequest = PendingIndService::find($id);
        $categories = IndCategory::all();
        $subCategories = IndSubCategory::all();
        $navs = $this->navs();
        return view('backend.ind-service-request.show', compact('serviceRequest', 'navs', 'categories', 'subCategories'));
    }

    public function store(StoreApproveIndService $request)
    {
        $pendingService = PendingIndService::find($request->post('id'));
        $pendingDocs = $pendingService->docs;
        $pendingImages = $pendingService->images;

        $service = new IndService;
        $service->user_id = $pendingService->user_id;
        $service->ind_category_id = $request->post('category');
        $service->district_id = $pendingService->district;
        $service->thana_id = $pendingService->thana;
        $service->union_id = $pendingService->union;

        $service->mobile = $pendingService->mobile;
        $service->email = $pendingService->email;
        $service->facebook = $pendingService->facebook;
        $service->website = $pendingService->website;
        $service->latitude = $pendingService->latitude;
        $service->longitude = $pendingService->longitude;
        $service->address = $pendingService->address;
        $service->save();

        // ind_subcategory_ind_service

        $subCategoryIds = IndCategory::find($request->post('category'))
            ->subCategories
            ->pluck('id');
        $subCategories = [];

        // TODO: Store Sub-categories from request->sub-categories
        foreach ($subCategoryIds as $subCategoryId) {
            array_push($subCategories, [
                'ind_sub_category_id' => $subCategoryId,
                'ind_service_id' => $service->id
            ]);
        }

        DB::table('ind_sub_category_ind_service')->insert($subCategories);


        // work_method_ind_service table

        $pendingWorkMethods = $pendingService->workMethods->pluck('id');
        $workMethods = [];
        foreach ($pendingWorkMethods as $workMethod) {
            array_push($workMethods, [
                'work_method_id' => $workMethod,
                'ind_service_id' => $pendingService->id
            ]);
        }
        DB::table('work_method_ind_service')->insert($workMethods);

        // attach role
        $user = User::find($service->user_id);
        if (!$user->hasRole('ind-service')) {
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
            ['route' => 'individual-service.index', 'text' => 'All Service Provider'],
            ['route' => 'individual-service-request.index', 'text' => 'Service Requests'],
            ['route' => 'individual-service.disabled', 'text' => 'Disabled Service Provider'],
        ];
    }
}
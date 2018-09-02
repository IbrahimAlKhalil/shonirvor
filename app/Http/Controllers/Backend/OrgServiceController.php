<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreOrgService;
use App\Models\OrgService;
use App\Models\OrgServiceDoc;
use App\Models\OrgServiceImage;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrgServiceController extends Controller
{

    public function index()
    {
        $orgServices = OrgService::paginate(15);
        return view('backend.org-service.index', compact('orgServices'));
    }


    public function create()
    {
        return view('backend.org-service.create');
    }


    public function store(StoreOrgService $request)
    {
        $user = new User;
        $user->name = $request->post('name');
        $user->mobile = $request->post('mobile');
        $user->password = bcrypt($request->post('password'));
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->photo = $request->file('photo')->store('user-photo');
        $user->age = $request->post('age');

        $user->save();
        $user->roles()->attach(4);


        $registration = new OrgService;
        $registration->user_id = $user->id;
        $registration->org_name = $request->post('org-name');
        $registration->mobile = $request->post('service-mobile');
        $registration->description = $request->post('description');
        $registration->email = $request->post('email');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');

        $registration->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('org-service-images/' . $registration->id),
                    'org_service_id' => $registration->id
                ]);
            }

            OrgServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('org-service-docs/' . $registration->id),
                    'org_service_id' => $registration->id
                ]);
            }

            OrgServiceDoc::insert($documents);
        }

        return back()->with('success', 'Organizational Service Provider Created Successfully!');
    }

    public function show(OrgService $orgService)
    {
        return view('backend.org-service.show', compact('orgService'));
    }

    public function destroy(Request $request, OrgService $orgService)
    {
        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $orgService->delete();
                    $msg = 'Account Deactivated Successfully!';
                    break;
                default:

                    // delete directories
                    Storage::deleteDirectory('org-service-docs/' . $orgService->id);
                    Storage::deleteDirectory('org-service-images/' . $orgService->id);

                    $orgService->forceDelete();

                    $msg = 'Account Removed Successfully!';
            }

            return redirect(route('org-service.show-disabled', $orgService->id))->with('success', $msg);
        }

        return abort('404');
    }


    public function showDisabledAccounts()
    {
        $orgServices = OrgService::onlyTrashed()->paginate(15);

        return view('backend.org-service.index', compact('orgServices'));
    }

    public function showDisabled($id)
    {
        $orgService = OrgService::withTrashed()->find($id);
        return view('backend.org-service.show', compact('orgService'));
    }

    public function activate(Request $request)
    {
        OrgService::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('org-service.show', $request->post('id')))->with('success', 'Account Activated Successfully!');
    }

}

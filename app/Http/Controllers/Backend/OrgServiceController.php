<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreOrgService;
use App\Models\OrgService;
use App\Models\OrgServiceDoc;
use App\Models\OrgServiceImage;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrgServiceController extends Controller
{
    public function index()
    {
        $orgServices = OrgService::paginate(15);
        $navs = $this->navs();
        return view('backend.org-service.index', compact('orgServices', 'navs'));
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
        $user->photo = $request->file('photo')->store('user-photos');
        $user->age = $request->post('age');
        $user->save();
        if (!$user->hasRole('org-service')) {
            $user->roles()->attach(4);
        }

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

    public function show($id)
    {
        $navs = $this->navs();
        $visitor['today'] = DB::table('org_service_visitor_counts')->where('org_service_id', $id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('org_service_visitor_counts')->where('org_service_id', $id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('org_service_visitor_counts')->where('org_service_id', $id)->whereYear('created_at', date('Y'))->sum('how_much');
        $orgService = OrgService::find($id);

        return view('backend.org-service.show', compact('orgService', 'navs', 'visitor'));
    }

    public function destroy(Request $request, $id)
    {
        $orgService = OrgService::find($id);
        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $orgService->delete();
                    $msg = 'Account Deactivated Successfully!';
                    $route = 'organization-service.show-disabled';
                    break;
                default:

                    // delete directories
                    Storage::deleteDirectory('org-service-docs/' . $orgService->id);
                    Storage::deleteDirectory('org-service-images/' . $orgService->id);


                    $user = User::find($orgService->user_id);

                    if (!$user->orgService()->count() <= 1) {
                        $user->roles()->detach('org-service');
                    }

                    $orgService->forceDelete();
                    $msg = 'Account Removed Successfully!';
                    $route = 'organization-service.index';
            }

            return redirect(route($route, $orgService->id))->with('success', $msg);
        }

        return abort('404');
    }

    public function showDisabledAccounts()
    {
        $orgServices = OrgService::onlyTrashed()->paginate(15);
        $navs = $this->navs();
        return view('backend.org-service.all-disabled', compact('orgServices', 'navs'));
    }

    public function showDisabled($id)
    {
        $orgService = OrgService::withTrashed()->find($id);
        $navs = $this->navs();
        return view('backend.org-service.one-disabled', compact('orgService', 'navs'));
    }

    public function activate(Request $request)
    {
        OrgService::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('organization-service.show', $request->post('id')))->with('success', 'Account Activated Successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-service.index'), 'text' => 'All Service Provider'],
            ['url' => route('organization-service-request.index'), 'text' => 'Service Requests'],
            ['url' => route('organization-service.disabled'), 'text' => 'Disabled Service Provider'],
        ];
    }
}
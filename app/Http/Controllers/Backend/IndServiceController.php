<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreIndService;
use App\Models\IndService;
use App\Models\IndServiceDoc;
use App\Models\IndServiceImage;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Notifications\AdminToUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class IndServiceController extends Controller
{
    public function index()
    {
        $indServices = IndService::paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service.index', compact('indServices', 'navs'));
    }

    public function create()
    {
        return view('backend.ind-service.create');
    }

    public function store(StoreIndService $request)
    {
        $user = new User;
        $user->name = $request->post('name');
        $user->mobile = $request->post('mobile');
        $user->password = bcrypt($request->post('password'));
        $user->email = $request->post('personal-email');
        $user->nid = $request->post('nid');
        $user->qualification = $request->post('qualification');
        $user->photo = $request->file('photo')->store('user-photos');
        $user->age = $request->post('age');

        $user->save();
        if (!$user->hasRole('ind-service')) {
            $user->roles()->attach(3);
        }

        $registration = new IndService;
        $registration->user_id = $user->id;
        $registration->email = $request->post('email');
        $registration->mobile = $request->post('service-mobile');
        $registration->latitude = $request->post('latitude');
        $registration->longitude = $request->post('longitude');
        $registration->service = $request->post('service');
        $registration->address = $request->post('address');

        $registration->save();

        if ($request->has('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                array_push($images, [
                    'image' => $image->store('inds-service-images/' . $registration->id),
                    'ind_service_id' => $registration->id
                ]);
            }

            IndServiceImage::insert($images);
        }

        if ($request->has('docs')) {
            $documents = [];

            foreach ($request->file('docs') as $document) {
                array_push($documents, [
                    'doc' => $document->store('ind-service-docs/' . $registration->id),
                    'ind_service_id' => $registration->id
                ]);
            }

            IndServiceDoc::insert($documents);
        }

        return back()->with('success', 'Individual Service Provider Created Successfully!');
    }


    public function show($id)
    {
        $navs = $this->navs();
        $visitor['today'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('ind_service_visitor_counts')->where('ind_service_id', $id)->whereYear('created_at', date('Y'))->sum('how_much');
        $indService = IndService::find($id);

        return view('backend.ind-service.show', compact('indService', 'navs', 'visitor'));
    }

    public function destroy(Request $request, $id)
    {
        $indService = IndService::find($id);

        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $indService->delete();
                    $msg = 'Account Deactivated Successfully!';
                    $route = 'individual-service.show-disabled';
                    break;
                default:

                    // delete directories
                    Storage::deleteDirectory('ind-service-docs/' . $indService->id);
                    Storage::deleteDirectory('ind-service-images/' . $indService->id);

                    User::find($indService->user_id)->roles()->detach('ind-service');

                    $indService->forceDelete();
                    $msg = 'Account Removed Successfully!';
                    $route = 'individual-service.index';
            }

            return redirect(route($route, $indService->id))->with('success', $msg);
        }

        return abort('404');
    }

    public function showDisabledAccounts()
    {
        $indServices = IndService::onlyTrashed()->paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service.all-disabled', compact('indServices', 'navs'));
    }

    public function showDisabled($id)
    {
        $indService = IndService::withTrashed()->find($id);
        $navs = $this->navs();
        return view('backend.ind-service.one-disabled', compact('indService', 'navs'));
    }

    public function activate(Request $request)
    {
        IndService::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('individual-service.show', $request->post('id')))->with('success', 'Account Activated Successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('individual-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('individual-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার']
        ];
    }
}
<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

// TODO:: Maybe some of these requests classes are empty or incomplete, so fill these with whatever you can.

class orgServiceController extends Controller
{
    public function index()
    {
        $orgs = Org::paginate(15);
        $navs = $this->navs();
        return view('backend.org-service.index', compact('orgs', 'navs'));
    }

    public function show(Org $org)
    {
        $navs = $this->navs();
        $visitor['today'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereDate('created_at', date('Y-m-d'))->sum('how_much');
        $visitor['thisMonth'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->sum('how_much');
        $visitor['thisYear'] = DB::table('org_visitor_counts')->where('org_id', $org->id)->whereYear('created_at', date('Y'))->sum('how_much');

        return view('backend.org-service.show', compact('org', 'navs', 'visitor'));
    }

    public function destroy(Request $request, Org $org)
    {
        DB::beginTransaction();

        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $org->delete();
                    $msg = 'Account Deactivated Successfully!';
                    $route = route('organization-service.show-disabled', $org->id);
                    break;
                default:

                    // TODO:: Delete images/docs after deleting account

                    $user = $org->user;

                    if (!$user->orgs('approved')->count() <= 1) {
                        $user->roles()->detach('org');
                    }

                    $org->forceDelete();
                    $msg = 'Account Removed Successfully!';
                    $route = route('organization-service.index');
            }

            DB::commit();
            return redirect($route)->with('success', $msg);
        }

        return abort('404');
    }

    public function showDisabledAccounts()
    {
        $orgs = Org::getOnly('approved')->onlyTrashed()->paginate(15);
        $navs = $this->navs();
        return view('backend.org-service.all-disabled', compact('orgs', 'navs'));
    }

    public function showDisabled($id)
    {
        $org = Org::withTrashed()->find($id);
        $navs = $this->navs();
        return view('backend.org-service.one-disabled', compact('org', 'navs'));
    }

    public function activate(Request $request)
    {
        Org::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('organization-service.show', $request->post('id')))->with('success', 'Account Activated Successfully!');
    }

    private function navs()
    {
        return [
            ['url' => route('organization-service.index'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('organization-service-request.index'), 'text' => 'সার্ভিস রিকোয়েস্ট'],
            ['url' => route('organization-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
        ];
    }
}
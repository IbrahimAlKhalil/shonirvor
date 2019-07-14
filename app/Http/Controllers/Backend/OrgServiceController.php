<?php

namespace App\Http\Controllers\Backend;

use App\Models\Org;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

// TODO:: Request Validation

class orgServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function show(Org $org)
    {
        $navs = $this->navs();

        return view('backend.org-service.show', compact('org', 'navs'));
    }

    public function showDisabled($id)
    {
        $org = Org::withTrashed()->findOrFail($id);
        $navs = $this->navs();

        return view('backend.org-service.one-disabled', compact('org', 'navs'));
    }

    public function destroy(Request $request, $id)
    {
        $org = Org::withTrashed()->findOrFail($id);
        DB::beginTransaction();

        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $org->delete();
                    $msg = 'অ্যাকাউন্টটি সফলভাবে নিষ্ক্রিয় করা হয়েছে!';
                    $route = route('organization-service.show-disabled', $org->id);
                    break;
                default:
                    // TODO:: Delete images/docs after deleting account

                    $user = $org->user;

                    if (!$user->orgs()->where('expire', '<>', null)->count() <= 1) {
                        $user->roles()->detach('org');
                    }

                    $org->forceDelete();
                    $msg = 'একাউন্ট সফলভাবে মুছে ফেলা হয়েছে!';
                    $route = route('service-filter');
            }

            DB::commit();
            return redirect($route)->with('success', $msg);
        }

        return abort('404');
    }

    public function activate(Request $request)
    {
        Org::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('organization-service.show', $request->post('id')))->with('success', 'একাউন্ট সফলভাবে সক্রিয় করা হয়েছে!');
    }

    public function isTop(Org $org, Request $request)
    {
        $org->is_top = $request->input('is_top');
        $org->save();

        if ($org->is_top) {
            $message = 'এই সার্ভিসকে এখন টপ সার্ভিসে রাখা হয়েছে।';
        } else {
            $message = 'এই সার্ভিসকে এখন টপ সার্ভিস থেকে সরিয়ে ফেলা হয়েছে।';
        }

        return back()->with('success', $message);
    }

    private function navs()
    {
        return [
            ['url' => route('service-filter'), 'text' => 'সকল সার্ভিস প্রভাইডার']
        ];
    }
}

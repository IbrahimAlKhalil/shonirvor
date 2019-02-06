<?php

namespace App\Http\Controllers\Backend;

use App\Models\Division;
use App\Models\Ind;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// TODO:: Request Validation

class indServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function show(Ind $ind)
    {
        $navs = $this->navs();

        return view('backend.ind-service.show', compact('ind', 'navs'));
    }

    public function destroy(Request $request, Ind $ind)
    {

        if ($request->post('type') == 'deactivate' || $request->post('type') == 'remove') {
            switch ($request->post('type')) {
                case 'deactivate':
                    $ind->delete();
                    $msg = 'Account Deactivated Successfully!';
                    $route = route('individual-service.show-disabled', $ind->id);
                    break;
                default:

                    // TODO:: Delete images/docs after deleting account
                    $user = $ind->user;

                    if (!$user->inds('approved')->count() <= 1) {
                        $user->roles()->detach('ind');
                    }

                    $ind->forceDelete();
                    $msg = 'Account Removed Successfully!';
                    $route = route('individual-service.index');
            }

            return redirect($route)->with('success', $msg);
        }

        return abort('404');
    }

    public function showDisabledAccounts()
    {
        $inds = Ind::onlyApproved()->onlyTrashed()->paginate(15);
        $navs = $this->navs();
        return view('backend.ind-service.all-disabled', compact('inds', 'navs'));
    }

    public function showDisabled($id)
    {
        $ind = Ind::withTrashed()->find($id);
        $navs = $this->navs();
        return view('backend.ind-service.one-disabled', compact('ind', 'navs'));
    }

    public function activate(Request $request)
    {
        Ind::onlyTrashed()->find($request->post('id'))->restore();
        return redirect(route('individual-service.show', $request->post('id')))->with('success', 'Account Activated Successfully!');
    }

    public function isTop(Ind $ind, Request $request)
    {
        $ind->is_top = $request->input('is_top');
        $ind->save();

        if ($ind->is_top) {
            $message = 'এই সার্ভিসকে এখন টপ সার্ভিসে রাখা হয়েছে।';
        } else {
            $message = 'এই সার্ভিসকে এখন টপ সার্ভিস থেকে সরিয়ে ফেলা হয়েছে।';
        }

        return back()->with('success', $message);
    }

    private function navs()
    {
        return [
            ['url' => route('service-filter'), 'text' => 'সকল সার্ভিস প্রভাইডার'],
            ['url' => route('individual-service.disabled'), 'text' => 'বাতিল সার্ভিস প্রভাইডার'],
        ];
    }
}
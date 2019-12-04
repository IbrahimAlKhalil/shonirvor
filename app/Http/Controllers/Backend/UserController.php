<?php

namespace App\Http\Controllers\Backend;

use App\Jobs\SendSms;
use App\Models\MessageTemplate;
use App\Models\User;
use App\Models\Expense;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\UserReferPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index()
    {
        $smsTemplates = MessageTemplate::select('id', 'name', 'message')->get();

        return view('backend.users.index', compact('users', 'smsTemplates'));
    }

    public function show(User $user)
    {
        $user->load(['references']);

        $referPackages = Package::with('properties')->where('package_type_id', 5)->get();

        $userReferPackageId = userReferrerPackage($user)->id;

        $paymentMethods = PaymentMethod::select('id', 'name')->get();

        $totalEarn = userTotalEarn($user);
        $totalPaymentGet = $user->earns()->where('expense_type_id', 1)->sum('amount');
        $payable = $totalEarn - $totalPaymentGet;

        return view('backend.users.show', compact('user', 'referPackages', 'userReferPackageId', 'paymentMethods', 'totalEarn', 'payable'));
    }

    public function filter(Request $request)
    {
        $keyword = $request->input('keyword', '');

        if (!$keyword) {
            $keyword = '';
        }

        $users = User::query()->select('id', 'name', 'mobile', 'photo')
            ->whereRaw("name REGEXP ? or mobile REGEXP ?", [$keyword, $keyword])
            ->paginate($request->input('per-page', 10));

        return $users;
    }

    public function sendSms(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'message' => 'required|string'
        ]);

        $ids = explode(',', $request->post('ids'));

        if (!User::whereIn('id', $ids)->exists()) {
            return abort(422);
        }

        $message = $request->post('message');
        $this->dispatch(new SendSms(array_unique($ids), $message));

        return '...';
    }

    public function updateReferPackage(User $user, Request $request)
    {
        if ($user->referPackage()->exists()) {

            if ($request->input('refer-id') == 1) {

                $user->referPackage->delete();
                return back()->with('success', 'রেফার প্যাকেজ পরিবর্তিত হয়েছে।');

            } else {

                $referPackage = $user->referPackage;

            }

        } else {

            $referPackage = new UserReferPackage;
            $referPackage->user_id = $user->id;

        }

        $referPackage->package_id = $request->input('refer-id');
        $referPackage->save();

        return back()->with('success', 'রেফার প্যাকেজ পরিবর্তিত হয়েছে।');
    }

    public function payReferrer(User $user, Request $request)
    {
        $expense = new Expense;
        $expense->user_id = $user->id;
        $expense->payment_method_id = $request->input('method');
        $expense->expense_type_id = 1;
        $expense->amount = $request->input('amount');
        $expense->from = $request->input('from');
        $expense->transactionId = $request->input('transaction-id');
        $expense->save();

        return back()->with('success', 'রেফারার পেমেন্ট হিসাবভুক্ত হয়েছে।');
    }

    public function destroy(User $user)
    {
        // TODO: Delete photos/documents
        $identities = $user->identities;

        $user->feedbacks()->delete();

        $user->delete();

        if ($user->photo != 'default/user-photo/person.jpg') {
            Storage::delete();
        }

        foreach ($identities as $identity) {
            Storage::delete($identity->path);
        }

        return redirect('dashboard')->with('success', 'ইউজারটি সফলভাবে মুছে ফেলা হয়েছে');
    }
}

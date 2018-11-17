<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Expense;
use App\Models\Package;
use function foo\func;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\UserReferPackage;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'mobile')->paginate(20);
        return view('backend.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['references.package']);

        $referPackages = Package::with('properties')->where('package_type_id', 5)->get();

        if ($user->referPackage()->exists()) {
            $userReferPackageId = $user->referPackage->package_id;
        } else {
            $userReferPackageId = 1; // default referrer package always be the first package.
        }

        $paymentMethods = PaymentMethod::select('id', 'name')->get();
        $paymentPackageIds = Package::whereIn('package_type_id', [1, 2])->pluck('id')->toArray();

        $totalEarn = 0;
        $user->references->each(function ($reference) use (&$totalEarn, $user, $paymentPackageIds) {
            if ( ! ($reference->target && $reference->target_start_time && $reference->target_end_time)) {
                $reference->service->payments()
                    ->whereIn('package_id', $paymentPackageIds)
                    ->where('approved', 1)
                    ->get()->each(function ($payment, $key) use (&$totalEarn, $reference) {
                        $fee = $payment->package->properties
                            ->where('name', 'fee')->first()->value;

                        if ( ! $key) {
                            $totalEarn += $fee * $reference->onetime_interest / 100;
                        } else {
                            $totalEarn += $fee * $reference->renew_interest / 100;
                        }
                        dump($totalEarn);
                    });
            } elseif ($reference->target_end_time->lt(now())) {
                $reference->service->payments()
                    ->whereIn('package_id', $paymentPackageIds)
                    ->where('approved', 1)
                    ->get()->each(function ($payment, $key) use (&$totalEarn, $user, $reference) {
                        $howManyReferred = $user->references()->whereDate('created_at', '>', $reference->target_start_time)
                            ->whereDate('created_at', '<', $reference->target_end_time)->count();

                        $fee = $payment->package->properties
                            ->where('name', 'fee')->first()->value;

                        if ($howManyReferred >= $reference->target) {
                            if ( ! $key) {
                                $totalEarn += $fee * $reference->onetime_interest / 100;
                            } else {
                                $totalEarn += $fee * $reference->renew_interest / 100;
                            }
                        } else {
                            if ( ! $key) {
                                $totalEarn += $fee * $reference->fail_onetime_interest / 100;
                            } else {
                                $totalEarn += $fee * $reference->fail_renew_interest / 100;
                            }
                        }
                    });
            }
        });

        $totalPaymentGet = $user->earns()->where('expense_type_id', 1)->sum('amount');
        $payable = $totalEarn - $totalPaymentGet;

        return view('backend.users.show', compact('user', 'referPackages', 'userReferPackageId', 'paymentMethods', 'totalEarn', 'payable'));
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
}
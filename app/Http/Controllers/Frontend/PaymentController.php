<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\Org;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $navs = [
            ['url' => route('backend.package.ind-service.index'), 'text' => 'ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-service.index'), 'text' => 'প্রাতিষ্ঠানিক সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.ind-top-service.index'), 'text' => 'ব্যাক্তিগত টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.org-top-service.index'), 'text' => 'প্রাতিষ্ঠানিক টপ সার্ভিস প্যাকেজসমূহ'],
            ['url' => route('backend.package.referrer.index'), 'text' => 'রেফারার প্যাকেজসমূহ'],
            ['url' => route('backend.package.ad.index'), 'text' => 'এড প্যাকেজসমূহ']
        ];

        $indServices = Ind::where('user_id', Auth::id())->with([
            'payments' => function ($query) {
                $query->join('packages', function ($join) {
                    $join->on('packages.id', 'incomes.package_id')->whereIn('packages.package_type_id', [1, 3]);
                });
            }
        ])->get();

        $orgServices = Org::where('user_id', Auth::id())->with([
            'payments' => function ($query) {
                $query->join('packages', function ($join) {
                    $join->on('packages.id', 'incomes.package_id')->whereIn('packages.package_type_id', [2, 4]);
                });
            }
        ])->get();

        $services = collect($orgServices)->merge(collect($indServices))->sortBy('expire');
        $topServices = $services->where('top_expire', '<', Carbon::now());

        $renewRequested = $services->where('expire', '<', Carbon::now())->filter(function ($item) {
            $ids = [5, 6];
            return !in_array($item->payments[0]->package_type_id, $ids) && $item->payments[0]->approved == 0;
        });

        return view('frontend.payment.index', compact('navs', 'services', 'renewRequested', 'topServices'));
    }
}

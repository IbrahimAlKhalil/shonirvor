<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ad;
use App\Models\Ind;
use App\Models\Org;
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
        $topServices = $services->where('top_expire', '!=', null);

        $renewRequestedServices = $services->where('expire', '!=', null)->filter(function ($item) {
            return $item->payments->sortBy('approved')->first()->approved == 0;
        });

        $ads = Ad::where('user_id', Auth::id())->with([
            'payments',
            'renewAsset'
        ])->get()->sortByDesc('payments.updated_at');

        $renewRequestedAds = $ads->where('expire', '!=', null)->filter(function ($item) {
            return $item->payments->sortByDesc('updated_at')->first()->approved == 0;
        });

        return view('frontend.payment.index', compact('navs', 'services', 'renewRequestedServices', 'topServices', 'ads', 'renewRequestedAds'));
    }
}

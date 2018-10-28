<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Ind;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
                $query->where('package_id', 1)->orderBy('created_at', 'DSC')->with([
                    'package' => function ($query) {
                        $query->with('properties');
                    }
                ]);
            }
        ])->get();

        return view('frontend.payment.index', compact('navs', 'indServices'));
    }
}

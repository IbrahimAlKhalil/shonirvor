@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@include('components.success')
@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">এডমিন ড্যাশবোর্ড</h1>
            </div>
        </div>
        <div class="row justify-content-around mt-3">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header">মোট ইউজার</div>
                    <div class="card-body">{{ en2bnNumber($userCount) }} জন</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header">SMS  বাকি আছে</div>
                    <div class="card-body">{{ en2bnNumber($smsBalance) }} টাকার</div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">সার্ভিস পরিসংখ্যান</div>
                    <div class="card-body">
                        <table class="table table-responsive-lg">
                            <thead>
                                <tr>
                                    <th class="border-0"></th>
                                    <th class="border-0">মোট</th>
                                    <th class="border-0">একটিভ</th>
                                    <th class="border-0">মেয়াদোত্তীর্ণ</th>
                                    <th class="border-0">নিষ্ক্রিয়</th>
                                    <th class="border-0">রেজিস্ট্রেশন পেন্ডিং</th>
                                    <th class="border-0">এডিট পেন্ডিং</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>বেক্তিগত সার্ভিস</th>
                                    <td>{{ en2bnNumber($inds->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('expire', '>', now())->where('deleted_at', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('expire', '<', now())->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('deleted_at', '!=', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('expire', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($serviceEdits->where('service_editable_type', 'ind')->count()) }} টি</td>
                                </tr>
                                <tr>
                                    <th>প্রাতিষ্ঠানিক সার্ভিস</th>
                                    <td>{{ en2bnNumber($orgs->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('expire', '>', now())->where('deleted_at', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('expire', '<', now())->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('deleted_at', '!=', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('expire', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($serviceEdits->where('service_editable_type', 'org')->count()) }} টি</td>
                                </tr>
                                <tr>
                                    <th>বিজ্ঞাপন</th>
                                    <td>{{ en2bnNumber($ads->count()) }} টি</td>
                                    <td>{{ en2bnNumber($ads->where('expire', '>', now())->count()) }} টি</td>
                                    <td>{{ en2bnNumber($ads->where('expire', '<', now())->count()) }} টি</td>
                                    <td>-</td>
                                    <td>{{ en2bnNumber($ads->where('expire', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($adEditCount) }} টি</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card text-center">
                    <div class="card-header">টপ সার্ভিস পরিসংখ্যান</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border-0"></th>
                                    <th class="border-0">মোট</th>
                                    <th class="border-0">একটিভ</th>
                                    <th class="border-0">মেয়াদোত্তীর্ণ</th>
                                    <th class="border-0">রেজিস্ট্রেশন পেন্ডিং</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>বেক্তিগত সার্ভিস</th>
                                    <td>{{ en2bnNumber($inds->where('top_expire', '!=', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('top_expire', '>', now())->where('deleted_at', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($inds->where('top_expire', '<', now())->count()) }} টি</td>
                                    <td>{{ en2bnNumber($indTopRequestCount) }} টি</td>
                                </tr>
                                <tr>
                                    <th>প্রাতিষ্ঠানিক সার্ভিস</th>
                                    <td>{{ en2bnNumber($orgs->where('top_expire', '!=', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('top_expire', '>', now())->where('deleted_at', null)->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgs->where('top_expire', '<', now())->count()) }} টি</td>
                                    <td>{{ en2bnNumber($orgTopRequestCount) }} টি</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
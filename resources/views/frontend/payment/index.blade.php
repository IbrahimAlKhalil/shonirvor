@extends('layouts.frontend.master')

@section('title', 'Payments')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

<div class="main-container">
@section('content')
    @include('components.success')
    <div class="container mt-5 bg-white rounded">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#service" class="nav-link active" data-toggle="tab">সার্ভিস</a>
                    </li>
                    <li class="nav-item">
                        <a href="#ad" class="nav-link" data-toggle="tab">এড</a>
                    </li>
                </ul>
                <div class="tab-content mt-5">
                    <div class="tab-pane fade active show" id="service">


                        @if($renewRequestedServices->count())
                            <h4 class="mt-5 text-center">রিনিউ চলছে</h4>
                            <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ক্যাটাগরি/নাম</th>
                                    <th scope="col">Transaction ID</th>
                                    <th scope="col">পদক্ষেপ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 1)
                                @foreach($renewRequestedServices as $service)
                                    @php($payment = $service->payments->first())
                                    <tr>
                                        <th scope="row">{{ en2bnNumber($count++) }}</th>
                                        <td>
                                            <a href="{{ route('frontend.my-service.ind.show', $service->id) }}">{{ $service->name?$service->name:$service->category->name }}</a>
                                        </td>
                                        <td>
                                            {{ $payment->transactionId }}
                                        </td>
                                        <td>
                                            <a href=""
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif


                        @if($topServices->count())
                            <h4 class="mt-5 text-center">টপ সার্ভিস</h4>
                            <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ক্যাটাগরি/নাম</th>
                                    <th scope="col">অবস্থা</th>
                                    <th scope="col">মেয়াদ উত্তীর্ণের তারিখ</th>
                                    <th scope="col">পদক্ষেপ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 1)
                                @foreach($topServices as $service)
                                    @php($serviceType = (new ReflectionClass($service))->getShortName())
                                    @php($payment = $service->payments->where('package_type_id', $serviceType == 'Ind'?3:4)->first())
                                    @php($pending = is_null($service->expire))
                                    @php($start = $expire = $expired = null)

                                    @if($payment && !$pending)
                                        @php($expire = \Carbon\Carbon::parse($service->expire))
                                        @php($remaining = $expire->diffInDays($start))
                                        @php($expired = $expire->lessThan(now()))
                                    @endif
                                    @php($status = $pending?['আবেদনকৃত', 'badge-primary']:($expired?['মেয়াদ শেষ', 'badge-danger']:['একটিভ', 'badge-success']))
                                    <tr>
                                        <th scope="row">{{ en2bnNumber($count++) }}</th>
                                        <td>
                                            <a href="{{ route('frontend.my-service.ind.show', $service->id) }}">{{ $service->name?$service->name:$service->category->name }}</a>
                                        </td>
                                        <td>
                                            <span class="badge {{ $status[1] }}">{{ $status[0] }}</span>
                                        </td>
                                        <td>
                                            {{ $expire?en2bnNumber($expire->format('d/m/Y')):'n/a' }}
                                        </td>
                                        <td>
                                            @if(!$pending)
                                                <a href="javascript:"
                                                   class="btn btn-outline-success btn-sm  @if($payment && ($payment->package_type_id != 5 || $payment->package_type_id != 6) && $payment->approved == 0){{ 'disabled' }}@endif">
                                                    <i class="fa fa-repeat"></i> নবীকরণ
                                                </a>
                                            @else
                                                <a href="{{ route('frontend.my-service.ind.edit', $service->id) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fa fa-pencil"></i> এডিট
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif


                        <h4 class="mt-5 text-center">সকল সার্ভিস</h4>
                        <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ক্যাটাগরি/নাম</th>
                                <th scope="col">অবস্থা</th>
                                <th scope="col">মেয়াদ উত্তীর্ণের তারিখ</th>
                                <th scope="col">পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 1)
                            @foreach($services as $index => $service)
                                @php($serviceType = (new ReflectionClass($service))->getShortName())
                                @php($payment = $service->payments->where('package_type_id', ($serviceType == 'Ind'?1:2))->first())
                                @php($pending = is_null($service->expire))
                                @php($start = $expire = $expired = null)

                                @if($payment && !$pending)
                                    @php($expire = \Carbon\Carbon::parse($service->expire))
                                    @php($expired = $expire->lessThan(now()))
                                @endif

                                @php($status = $pending?['আবেদনকৃত', 'badge-primary']:($expired?['মেয়াদ শেষ', 'badge-danger']:['একটিভ', 'badge-success']))
                                <tr>
                                    <th scope="row">{{ en2bnNumber($count++) }}</th>
                                    <td>
                                        <a href="{{ route('frontend.my-service.ind.show', $service->id) }}">{{ $service->name?$service->name:$service->category->name }}</a>
                                    </td>
                                    <td>
                                        <span class="badge {{ $status[1] }}">{{ $status[0] }}</span>
                                    </td>
                                    <td>
                                        {{ $expire?en2bnNumber($expire->format('d/m/Y')):'n/a' }}
                                    </td>
                                    <td>
                                        @if(!$pending)
                                            <a href="javascript:"
                                               class="btn btn-outline-success btn-sm  @if($payment && $payment->package_type_id == 1 && $payment->approved == 0){{ 'disabled' }}@endif">
                                                <i class="fa fa-repeat"></i> নবীকরণ
                                            </a>
                                        @else
                                            <a href="{{--{{ route('frontend.my-service.ind.edit', $service->id) }}--}}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="ad">
                        @if($renewRequestedAds->count())
                            <h4 class="mt-5 text-center">রিনিউ চলছে</h4>
                            <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">ছবি</th>
                                    <th scope="col">লিঙ্ক</th>
                                    <th scope="col">পদক্ষেপ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 1)
                                @foreach($renewRequestedAds as $index => $ad)
                                    @php($start = $expire = $expired = null)
                                    <tr>
                                        <th scope="row">{{ en2bnNumber($count++) }}</th>
                                        <td>
                                            <img class="img-thumbnail" style="width: 100px"
                                                 src="{{ asset('storage/' . $ad->image) }}">
                                        </td>
                                        <td>
                                            {{ $ad->url }}
                                        </td>
                                        <td>
                                            <a href="{{ route('frontend.applications.ad.edit', $ad->id) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif


                        <h4 class="mt-5 text-center">সকল এড</h4>
                        <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ছবি</th>
                                <th scope="col">লিঙ্ক</th>
                                <th scope="col">অবস্থা</th>
                                <th scope="col">মেয়াদ উত্তীর্ণের তারিখ</th>
                                <th scope="col">পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 1)
                            @forelse($ads as $index => $ad)
                                @php($pending = is_null($ad->expire))
                                @php($payment = $ad->payments->first())
                                @php($start = $expire = $expired = null)

                                @if(!$pending)
                                    @php($expire = \Carbon\Carbon::parse($ad->expire))
                                    @php($expired = $expire->lessThan(now()))
                                @endif

                                @php($status = $pending?['আবেদনকৃত', 'badge-primary']:($expired?['মেয়াদ শেষ', 'badge-danger']:['একটিভ', 'badge-success']))
                                <tr>
                                    <th scope="row">{{ en2bnNumber($count++) }}</th>
                                    <td>
                                        <img class="img-thumbnail" style="width: 100px"
                                             src="{{ asset('storage/' . $ad->image) }}">
                                    </td>
                                    <td>
                                        {{ $ad->url }}
                                    </td>
                                    <td>
                                        <span class="badge {{ $status[1] }}">{{ $status[0] }}</span>
                                    </td>
                                    <td>
                                        {{ $expire?en2bnNumber($expire->format('d/m/Y')):'n/a' }}
                                    </td>
                                    <td>
                                        @if(!$pending)
                                            <a href="javascript:"
                                               class="btn btn-outline-success btn-sm  @if($expired && $payment && $payment->approved == 0){{ 'disabled' }}@endif">
                                                <i class="fa fa-repeat"></i> নবীকরণ
                                            </a>
                                        @elseif(!$expired)
                                            <a href=""
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">কোন এড নেই</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 text-center mt-3">
                                <a href="{{ route('frontend.applications.ad.index') }}"
                                   class="btn btn-primary">বিজ্ঞাপনের জন্য
                                    আবেদন করুন</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
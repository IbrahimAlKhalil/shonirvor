@extends('layouts.frontend.master')

@section('title', 'Payments')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container mt-5 bg-white py-3 rounded">
        <div class="row">
            <div class="col-md-9">
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
                        <h4 class="mt-5 text-center">রিনিউ চলছে</h4>
                        <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ক্যাটাগরি/নাম</th>
                                <th scope="col">Transaction ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($count = 1)
                            @foreach($renewRequested as $service)
                                @php($payment = $service->payments->first())
                                <tr>
                                    <th scope="row">{{ en2bnNumber($count++) }}</th>
                                    <td>
                                        <a href="{{ route('frontend.my-service.ind.show', $service->id) }}">{{ $service->name?$service->name:$service->category->name }}</a>
                                    </td>
                                    <td>
                                        {{ $payment->transactionId }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

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
                                @php($start = $expire = $remaining = $expired = null)

                                @if($payment && !$pending)
                                    @php($start = $payment->updated_at)
                                    @php($expire = \Carbon\Carbon::parse($service->expire))
                                    @php($remaining = $expire->diffInDays($start))
                                    @php($expired = $expire->lessThan(\Carbon\Carbon::now()))
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
                                @php($start = $expire = $remaining = $expired = null)

                                @if($payment && !$pending)
                                    @php($start = $payment->updated_at)
                                    @php($expire = \Carbon\Carbon::parse($service->expire))
                                    @php($remaining = $expire->diffInDays($start))
                                    @php($expired = $expire->lessThan(\Carbon\Carbon::now()))
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
                        <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ক্যাটাগরি</th>
                                <th scope="col">বর্তমান প্যাকেজ</th>
                                <th scope="col">মেয়াদ উত্তীর্ণের তারিখ</th>
                                <th scope="col">পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <a href="{{ route('frontend.applications.ad.create') }}" class="btn btn-primary">বিজ্ঞাপনের জন্য আবেদন করুন</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
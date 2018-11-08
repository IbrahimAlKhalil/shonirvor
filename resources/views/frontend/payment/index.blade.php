@extends('layouts.frontend.master')

@section('title', 'Payments')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection
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
                        <a href="#ad" class="nav-link" data-toggle="tab">বিজ্ঞাপন</a>
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
                                    @php($serviceType = (new ReflectionClass($service))->getShortName())
                                    @php($payment = $service->payments->sortBy('approved')->first())
                                    @php($prefix = $serviceType == 'Ind'?'individual':'organization')
                                    @php($top = in_array($payment->package->package_type_id, [3, 4])?'top-':'' )
                                    <tr>
                                        <th scope="row">{{ en2bnNumber($count++) }}</th>
                                        <td>
                                            <a href="{{ route('frontend.my-service.ind.show', $service->id) }}">{{ $service->name?$service->name:$service->category->name }}</a>
                                        </td>
                                        <td>
                                            {{ $payment->transactionId }}
                                        </td>
                                        <td>
                                            <a href="{{ route("frontend.applications.$prefix-" . $top . "service.index") }}?service={{ $service->id }}"
                                               target="_blank"
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
                                @forelse($topServices as $index => $service)
                                    @php($serviceType = (new ReflectionClass($service))->getShortName())
                                    @php($prefix = $serviceType == 'Ind'?'individual':'organization')
                                    @php($payment = $service->payments->sortBy('approved')->first())
                                    @php($expire = $expired = null)
                                    @php($expire = \Carbon\Carbon::parse($service->top_expire))
                                    @php($expired = $expire->lessThan(now()))

                                    @php($status = $expired?['মেয়াদ শেষ', 'badge-danger']:['একটিভ', 'badge-success'])
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
                                            <a href="{{ route("frontend.applications.$prefix-top-service.index") }}?service={{ $service->id }}"
                                               target="_blank"
                                               class="btn btn-outline-success btn-sm  @if($payment->approved == 0){{ 'disabled' }}@endif">
                                                <i class="fa fa-repeat"></i> নবীকরণ
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            কোন সার্ভিস নেই
                                        </td>
                                    </tr>
                                @endforelse
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
                            @forelse($services as $index => $service)
                                @php($serviceType = (new ReflectionClass($service))->getShortName())
                                @php($prefix = $serviceType == 'Ind'?'individual':'organization')
                                @php($payment = $service->payments->sortBy('approved')->first())
                                @php($pending = is_null($service->expire))
                                @php($expire = $expired = null)

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
                                            <a href="{{ route("frontend.applications.$prefix-service.index") }}?service={{ $service->id }}"
                                               target="_blank"
                                               class="btn btn-outline-success btn-sm  @if($payment->approved == 0){{ 'disabled' }}@endif">
                                                <i class="fa fa-repeat"></i> নবীকরণ
                                            </a>
                                        @else
                                            <a href="{{ route("$prefix-service-registration.edit", $service->id) }}"
                                               class="btn btn-outline-primary btn-sm" target="_blank">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        কোন সার্ভিস নেই
                                    </td>
                                </tr>
                            @endforelse
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
                                            <a href="{{ route('frontend.applications.ad-renew.show', $ad->id) }}"
                                               target="_blank"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif


                        <h4 class="mt-5 text-center">সকল বিজ্ঞাপন</h4>
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
                                @php($payment = $ad->payments->sortByDesc('updated_at')->first())
                                @php($renew = !is_null($ad->expire) && $payment->approved == 0)
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
                                            <a href="{{ route('frontend.ad.edit', $ad->id) }}"
                                               target="_blank"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                            <a href="{{ route('frontend.applications.ad-renew.show', $ad->id) }}"
                                               target="_blank"
                                               class="btn btn-outline-success btn-sm  @if($payment->approved == 0){{ 'disabled' }}@endif">
                                                <i class="fa fa-repeat"></i> নবীকরণ
                                            </a>
                                        @elseif($pending)
                                            <a href="{{ route('frontend.applications.ad.index') }}"
                                               target="_blank"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-pencil"></i> এডিট
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">কোন বিজ্ঞাপন নেই</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12 text-center mt-3">
                                <a href="{{ route('frontend.applications.ad.index') }}"
                                   class="btn btn-primary">বিজ্ঞাপনের জন্য আবেদন করুন</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
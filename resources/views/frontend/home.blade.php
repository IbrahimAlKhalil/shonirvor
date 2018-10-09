@extends('layouts.frontend.master')

@section('title', 'হোম')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.slider')
    @include('components.notice')

    <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-md-2">
                @include('components.filter')
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-12">
                        @include('components.search')
                    </div>
                </div>
                <div class="row mt-4 bg-white rounded shadow-sm pt-3">
                    <div class="col-12">
                        <p class="h4 border-bottom text-center">টপ ক্যাটাগরি</p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5 pl-2 service-type-title-left mb-4">বেক্তিগত সার্ভিস</p>
                        @foreach($indCategories as $category)
                            <p>
                                <img src="{{ asset('storage/'.$category->image) }}" class="img-fluid category-image">
                                <a href="{{ route('frontend.filter').'?category='.$category->id }}">{{ $category->name }} ({{ $category->ind_services_count }})</a>
                            </p>
                        @endforeach
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="h5 pr-2 service-type-title-right mb-4">প্রাতিষ্ঠানিক সার্ভিস</p>
                        @foreach($orgCategories as $category)
                            <p>
                                <a href="{{ route('frontend.filter').'?category='.$category->id }}">({{ $category->org_services_count }}) {{ $category->name }}</a>
                                <img src="{{ asset('storage/'.$category->image) }}" class="img-fluid category-image">
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="row mt-4 bg-white rounded shadow-sm pt-3">
                    <div class="col-12">
                        <p class="h4 border-bottom text-center">টপ সার্ভিস প্রভাইডার</p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5 pl-2 service-type-title-left mb-4">বেক্তিগত সার্ভিস</p>
                        @foreach($indServices as $key => $service)
                            <ul class="list-unstyled">
                                <li class="media mt-3 p-2 service-card-shadow">
                                    <img class="mr-3 w-25 shadow-sm" src="{{ asset('storage/'.$service->user->photo) }}" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.ind-service.show', $service->id) }}">{{ $service->user->name }}</a>
                                            <input id="topIndStar{{ $key }}" value="{{ $service->feedbacks_avg }}" class="invisible">
                                        </p>
                                        <p>
                                            <i>{{ $service->category->name }}</i>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fa fa-phone"></span> {{ $service->mobile }}
                                            <br>
                                            <span class="fa fa-map-marker"></span> {{ $service->union->name }}, {{ $service->thana->name }}, {{ $service->district->name }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="h5 pr-2 service-type-title-right mb-4">প্রাতিষ্ঠানিক সার্ভিস</p>
                        @foreach($orgServices as $service)
                            <ul class="list-unstyled">
                                <li class="media mt-3 p-2 service-card-shadow">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.org-service.show', $service->id) }}">{{ $service->name }}</a>
                                            <input id="topOrgStar{{ $key }}" value="{{ $service->feedbacks_avg }}" class="invisible">
                                        </p>
                                        <p>
                                            <i>{{ $service->category->name }}</i>
                                        </p>
                                        <p class="mb-0">
                                            {{ $service->mobile }} <span class="fa fa-phone flipX"></span>
                                            <br>
                                            {{ $service->union->name }}, {{ $service->thana->name }}, {{ $service->district->name }} <span class="fa fa-map-marker"></span>
                                        </p>
                                    </div>
                                    <img class="ml-3 w-25 shadow-sm" src="{{ asset('storage/'.$service->logo) }}" alt="logo">
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.ad')
            </div>
        </div>
    </div>
@endsection
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
                        <form action="{{ route('frontend.filter') }}">
                            <div class="input-group">
                                <input type="text" name="area" class="form-control" placeholder="এলাকা সার্চ করুন">
                                <input type="text" name="category" class="form-control" placeholder="ক্যাটাগরি সার্চ করুন">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">সার্চ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom text-center">টপ ক্যাটাগরি</p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5 pl-2 service-type-title-left mb-4">বেক্তিগত সার্ভিস</p>
                        @foreach($indCategories as $category)
                            <p>
                                <img src="{{ asset('storage/'.$category->image) }}" class="img-fluid category-image">
                                <a href="#">{{ $category->name }}</a>
                            </p>
                        @endforeach
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="h5 pr-2 service-type-title-right mb-4">প্রাতিষ্ঠানিক সার্ভিস</p>
                        @foreach($orgCategories as $category)
                            <p>
                                <a href="#">{{ $category->name }}</a>
                                <img src="{{ asset('storage/'.$category->image) }}" class="img-fluid category-image">
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom text-center">টপ সার্ভিস প্রভাইডার</p>
                    </div>
                    <div class="col-md-6">
                        <p class="h5 pl-2 service-type-title-left mb-4">বেক্তিগত সার্ভিস</p>
                        @foreach($indServices as $service)
                            <ul class="list-unstyled">
                                <li class="media mt-3 p-2 bg-light">
                                    <img class="mr-3 w-25 shadow-sm" src="{{ asset('storage/'.$service->user->photo) }}" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.ind-service.show', $service->id) }}">{{ $service->user->name }}</a>
                                        </p>
                                        <p>
                                            <i>{{ str_limit($service->category->name, 25) }}</i>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fa fa-phone"></span> {{ $service->mobile }}
                                            <br>
                                            <span class="fa fa-map-marker"></span> {{ $service->union->bn_name }}, {{ $service->thana->bn_name }}, {{ $service->district->bn_name }}
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
                                <li class="media mt-3 p-2 bg-light">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.org-service.show', $service->id) }}">{{ str_limit($service->name, 20) }}</a>
                                        </p>
                                        <p>
                                            <i>{{ str_limit($service->category->name, 25) }}</i>
                                        </p>
                                        <p class="mb-0">
                                            {{ $service->mobile }} <span class="fa fa-phone" style="transform: rotateY(180deg)"></span>
                                            <br>
                                            {{ $service->union->bn_name }}, {{ $service->thana->bn_name }}, {{ $service->district->bn_name }} <span class="fa fa-map-marker"></span>
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
                @foreach($ads as $ad)
                    <div class="row">
                        <div class="col-12 mb-5">
                            <a href="{{ $ad->url }}" target="_blank">
                                <img src="{{ asset('storage/'.$ad->image) }}" class="img-fluid rounded">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
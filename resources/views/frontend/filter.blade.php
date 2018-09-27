@extends('layouts.frontend.master')

@section('title', 'সার্ভিস সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/filter.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">ফিল্টার করুন</div>
                            <form action="{{ route('frontend.filter') }}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="division">এলাকা</label>
                                        <select name="division" id="division" class="form-control mb-3">
                                            <option value="">-- বিভাগ --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="district" id="district" class="form-control mb-3">
                                            <option value="">-- জেলা --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="thana" id="thana" class="form-control mb-3">
                                            <option value="">-- থানা --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="union" id="union" class="form-control mb-3">
                                            <option value="">-- ইউনিয়ন --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="category">ক্যাটাগরি</label>
                                        <select name="category" id="category" class="form-control mb-3">
                                            <option value="">-- ক্যাটাগরি --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                        <select name="sub-category" id="subCategory" class="form-control">
                                            <option value="">-- সাব ক্যাটাগরি --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">ফিল্টার</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('frontend.filter') }}">
                            <div class="input-group">
                                <input type="text" name="category" class="form-control" placeholder="ক্যাটাগরি সার্চ করুন">
                                <input type="text" name="area" class="form-control" placeholder="এলাকা সার্চ করুন">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="submit">সার্চ</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-4">
                    @foreach($providers as $provider)
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="media mt-3 p-2 bg-light">
                                    <img class="mr-3 w-25 shadow-sm" src="{{ asset('storage/'.$provider->user->photo) }}" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.ind-service.show', $provider->id) }}">{{ $provider->user->name }}</a>
                                        </p>
                                        <p>
                                            <i>{{ str_limit($provider->category->name, 25) }}</i>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fa fa-phone"></span> {{ $provider->mobile }}
                                            <br>
                                            <span class="fa fa-map-marker"></span> {{ $provider->district->bn_name }}, {{ $provider->thana->bn_name }}, {{ $provider->union->bn_name }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endforeach
                </div>
                {{--<div class="row">--}}
                    {{--<div class="mx-auto">--}}
                        {{--{{ $providers->links('pagination::bootstrap-4') }}--}}
                    {{--</div>--}}
                {{--</div>--}}
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
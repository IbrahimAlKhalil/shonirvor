@extends('layouts.frontend.master')

@section('title', 'হোম')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.slider')

    <div class="container-fluid">
        <div class="row pt-4">
            <div class="col-md-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">ফিল্টার করুন</div>
                            <form action="#">
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
                        <form action="">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="এখানে সার্চ করুন">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit">সার্চ</button>
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
                                <a href="#">{{ $category->name }}</a>
                            </p>
                        @endforeach
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="h5 pr-2 service-type-title-right mb-4">প্রাতিষ্ঠানিক সার্ভিস</p>
                        @foreach($orgCategories as $category)
                            <p>
                                <a href="#">{{ $category->name }}</a>
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
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae, dolores!
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
                                            <a href="{{ route('frontend.ind-service.show', $service->id) }}">{{ str_limit($service->name, 20) }}</a>
                                        </p>
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Beatae, dolores!
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
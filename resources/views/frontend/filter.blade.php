@extends('layouts.frontend.master')

@section('title', 'সার্ভিস সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/filter.bundle.js') }}"></script>
@endsection

@section('content')
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
                                    <img class="mr-3 w-25 shadow-sm" src="{{ asset('storage/'.$provider->photo) }}" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.'.$provider->type.'-service.show', $provider->id) }}">{{ $provider->name }}</a>
                                        </p>
                                        <p>
                                            <i>{{ str_limit($provider->category_name, 25) }}</i>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fa fa-phone"></span> {{ $provider->mobile }}
                                            <br>
                                            <span class="fa fa-map-marker"></span> {{ $provider->district_name }}, {{ $provider->thana_name }}, {{ $provider->union_name }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    @endforeach
                </div>
                <ul class="pagination justify-content-center">
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                </ul>
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
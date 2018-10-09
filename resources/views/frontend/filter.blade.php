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
                        @include('components.search')
                    </div>
                </div>
                <div class="row mt-4 bg-white rounded">
                    @forelse($providers as $key => $provider)
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="media mt-3 p-2 service-card-shadow">
                                    <img class="mr-3 w-25 shadow-sm" src="{{ asset('storage/'.$provider->photo) }}" alt="Generic placeholder image">
                                    <div class="media-body">
                                        <p class="mt-0 h5">
                                            <a href="{{ route('frontend.'.$provider->type.'-service.show', $provider->id) }}">{{ $provider->name }}</a>
                                            <input id="star{{ $key }}" value="{{ $provider->feedbacks_avg }}" class="invisible">
                                        </p>
                                        <p>
                                            <i>{{ $provider->category_name, 25 }}</i>
                                        </p>
                                        <p class="mb-0">
                                            <span class="fa fa-phone"></span> {{ $provider->mobile }}
                                            <br>
                                            <span class="fa fa-map-marker"></span> {{ $provider->union_name }}, {{ $provider->thana_name }}, {{ $provider->district_name }}
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    @empty
                        <div class="col-12 pt-5">
                            <p class="text-center h4"> আপনার ফিল্টার অনুযায়ী কোন সার্ভিস প্রভাইডার পাওয়া যায়নি।</p>
                        </div>
                    @endforelse
                </div>
                <div class="row">
                    <div class="mx-auto">
                        {{ $providers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.ad')
            </div>
        </div>
    </div>
@endsection
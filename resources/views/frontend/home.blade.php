@extends('layouts.frontend.master')

@section('title', 'হোম')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.slider')
    @include('components.notice')

    <div class="container-fluid">
        <div class="row pt-4 justify-content-center">
            <div class="col-lg-11 pl-lg-0">
                @include('components.filter')
            </div>
        </div>
        <div class="row pt-4 justify-content-center">
            <div class="col-lg-8">
                <div class="row bg-white rounded shadow-sm pt-3">
                    <div class="col-12">
                        <p class="h4 border-bottom text-center text-white font-weight-bold heading-color p-2 rounded shadow-sm">
                            টপ ক্যাটাগরি</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="h5 pl-2 text-center mb-4 font-weight-bold">ব্যক্তিগত সার্ভিস</p>
                        <div class="row border-right">
                            @foreach($indCategories as $category)
                                <div class="col-4 text-center">
                                    <img src="{{ asset('storage/'.$category->image) }}" alt="img-fluid" class="c-icon">
                                    <p>
                                        <a href="{{ route('frontend.filter').'?category='.$category->id }}">
                                            <span class="small-text">{{ $category->name }} ({{ $category->ind_services_count }})</span>
                                        </a>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-right">
                        <p class="h5 pl-2 pl-lg-0 pr-lg-2 text-center mb-4 font-weight-bold">প্রাতিষ্ঠানিক সার্ভিস</p>
                        <div class="row border-left">
                            @foreach($orgCategories as $category)
                                <div class="col-4 text-center">
                                    <img src="{{ asset('storage/'.$category->image) }}" alt="img-fluid" class="c-icon">
                                    <p>
                                        <a href="{{ route('frontend.filter').'?category='.$category->id }}">
                                            <span class="small-text">{{ $category->name }} ({{ $category->org_services_count }})</span>
                                        </a>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if($indTopServices->isNotEmpty() || $orgTopServices->isNotEmpty())
                    <div class="row mt-4 bg-white rounded shadow-sm pt-3">
                        <div class="col-12">
                            <p class="h4 border-bottom text-center text-white bg-info shadow-sm rounded heading-color">
                                টপ সার্ভিস প্রভাইডার</p>
                        </div>
                        <div class="col-lg-6">
                            <p class="h5 pl-2 text-center mb-4 font-weight-bold heading-color">ব্যক্তিগত সার্ভিস</p>
                            <ul class="list-unstyled">
                                @foreach($indTopServices as $key => $service)
                                    <li class="media mt-3 p-2 service-card-shadow">
                                        <img class="mr-3 w-25 shadow-sm"
                                             src="{{ asset('storage/'.$service->user->photo) }}">
                                        <div class="media-body">
                                            <p class="mt-0 h5">
                                                <a href="{{ route('frontend.ind-service.show', $service->slug) }}">{{ $service->user->name }}</a>
                                                <input id="topIndStar{{ $key }}" value="{{ $service->feedbacks_avg }}"
                                                       class="invisible">
                                            </p>
                                            <p>
                                                <i>{{ $service->category->name }}</i>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fa fa-phone"></span> {{ $service->mobile }}
                                                <br>
                                                <span class="fa fa-map-marker"></span> {{ $service->union->name }}
                                                , {{ $service->thana->name }}, {{ $service->district->name }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-lg-6 text-lg-right">
                            <p class="h5 pl-2 pl-lg-0 pr-lg-2 text-center mb-4 heading-color font-weight-bold">
                                প্রাতিষ্ঠানিক সার্ভিস</p>
                            <ul class="list-unstyled">
                                @foreach($orgTopServices as $service)
                                    <li class="media mt-3 p-2 service-card-shadow">
                                        <img class="mr-3 w-25 shadow-sm d-inline-block d-lg-none"
                                             src="{{ asset('storage/'.$service->logo) }}" alt="logo">
                                        <div class="media-body">
                                            <p class="mt-0 h5">
                                                <a href="{{ route('frontend.org-service.show', $service->slug) }}">{{ $service->name }}</a>
                                                <input id="topOrgStar{{ $key }}" value="{{ $service->feedbacks_avg }}"
                                                       class="invisible">
                                            </p>
                                            <p>
                                                <i>{{ $service->category->name }}</i>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fa fa-phone d-inline-block d-lg-none"></span>
                                                {{ $service->mobile }}
                                                <span class="fa fa-phone flipX d-none d-lg-inline-block"></span>

                                                <br>

                                                <span class="fa fa-map-marker d-inline-block d-lg-none"></span>
                                                {{ $service->union->name }}, {{ $service->thana->name }}
                                                , {{ $service->district->name }}
                                                <span class="fa fa-map-marker d-none d-lg-inline-block"></span>
                                            </p>
                                        </div>
                                        <img class="ml-3 w-25 shadow-sm d-none d-lg-inline-block"
                                             src="{{ asset('storage/'.$service->logo) }}" alt="logo">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-3">
                @include('components.ad')
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Star Rating
        $('[id^="topIndStar"], [id^="topOrgStar"]').rating({
            step: 0.1,
            size: 'xm',
            displayOnly: true,
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            showClear: false,
            showCaptionAsTitle: false,
            clearCaptionClass: 'd-none',
            starCaptions: {
                1: '১',
                1.1: '১.১',
                1.2: '১.২',
                1.3: '১.৩',
                1.4: '১.৪',
                1.5: '১.৫',
                1.6: '১.৬',
                1.7: '১.৭',
                1.8: '১.৮',
                1.9: '১.৯',
                2: '২',
                2.1: '২.১',
                2.2: '২.২',
                2.3: '২.৩',
                2.4: '২.৪',
                2.5: '২.৫',
                2.6: '২.৬',
                2.7: '২.৭',
                2.8: '২.৮',
                2.9: '২.৯',
                3: '৩',
                3.1: '৩.১',
                3.2: '৩.২',
                3.3: '৩.৩',
                3.4: '৩.৪',
                3.5: '৩.৫',
                3.6: '৩.৬',
                3.7: '৩.৭',
                3.8: '৩.৮',
                3.9: '৩.৯',
                4: '৪',
                4.1: '৪.১',
                4.2: '৪.২',
                4.3: '৪.৩',
                4.4: '৪.৪',
                4.5: '৪.৫',
                4.6: '৪.৬',
                4.7: '৪.৭',
                4.8: '৪.৮',
                4.9: '৪.৯',
                5: '৫'
            },
            starCaptionClasses: {
                1: 'badge badge-pill badge-danger',
                1.1: 'badge badge-pill badge-danger',
                1.2: 'badge badge-pill badge-danger',
                1.3: 'badge badge-pill badge-danger',
                1.4: 'badge badge-pill badge-danger',
                1.5: 'badge badge-pill badge-warning',
                1.6: 'badge badge-pill badge-warning',
                1.7: 'badge badge-pill badge-warning',
                1.8: 'badge badge-pill badge-warning',
                1.9: 'badge badge-pill badge-warning',
                2: 'badge badge-pill badge-warning',
                2.1: 'badge badge-pill badge-warning',
                2.2: 'badge badge-pill badge-warning',
                2.3: 'badge badge-pill badge-warning',
                2.4: 'badge badge-pill badge-warning',
                2.5: 'badge badge-pill badge-info',
                2.6: 'badge badge-pill badge-info',
                2.7: 'badge badge-pill badge-info',
                2.8: 'badge badge-pill badge-info',
                2.9: 'badge badge-pill badge-info',
                3: 'badge badge-pill badge-info',
                3.1: 'badge badge-pill badge-info',
                3.2: 'badge badge-pill badge-info',
                3.3: 'badge badge-pill badge-info',
                3.4: 'badge badge-pill badge-info',
                3.5: 'badge badge-pill badge-primary',
                3.6: 'badge badge-pill badge-primary',
                3.7: 'badge badge-pill badge-primary',
                3.8: 'badge badge-pill badge-primary',
                3.9: 'badge badge-pill badge-primary',
                4: 'badge badge-pill badge-primary',
                4.1: 'badge badge-pill badge-primary',
                4.2: 'badge badge-pill badge-primary',
                4.3: 'badge badge-pill badge-primary',
                4.4: 'badge badge-pill badge-primary',
                4.5: 'badge badge-pill badge-success',
                4.6: 'badge badge-pill badge-success',
                4.7: 'badge badge-pill badge-success',
                4.8: 'badge badge-pill badge-success',
                4.9: 'badge badge-pill badge-success',
                5: 'badge badge-pill badge-success'
            }
        });


        // Selectize
        $('#division, #district, #thana, #union, #village, #category, #subCategory, #service-type, #method, #price').selectize({
            plugins: [
                'option-loader'
            ]
        });
    </script>
@endsection
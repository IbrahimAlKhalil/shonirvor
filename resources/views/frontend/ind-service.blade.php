@extends('layouts.frontend.master')

@section('title', $provider->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/ind-service.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container bg-white shadow">
        <div class="row">
            <img class="cover" src="{{ asset('storage/'.$provider->cover_photo) }}"/>
        </div>
        <div class="row py-3">
            <div class="col-lg-3 text-center text-lg-right">
                <img class="pp img-thumbnail" src="{{ asset('storage/'.$provider->user->photo) }}"/>
            </div>
            <div class="col-lg-6">
                <h1 class="text-center text-lg-left mt-3 mt-lg-0">{{ $provider->user->name }}</h1>
                <p class="h5 text-center text-lg-left mt-3">{{ $provider->category->name }}</p>
                <p class="h5 text-center text-lg-left mt-3 mt-lg-0">{{ $provider->village->bn_name.', '.$provider->union->bn_name.', '.$provider->thana->bn_name.', '.$provider->district->bn_name.', '.$provider->division->bn_name }}</p>
            </div>
            <div class="col-lg-3 pt-3 pl-5">
                <span class="fa-stack d-none d-lg-inline-block">
                  <i class="fa fa-star fa-stack-2x text-{{ $avgFeedbackColor }}"></i>
                  <i class="fa-stack-1x">{{ en2bnNumber( round($provider->feedbacks_avg, 1) ) }}</i>
                </span>
            </div>
            <div class="col-md-12">
                <div class="text-center">
                    @auth()
                        <a class="btn btn-primary fa fa-comments text-white my-2"
                           href="{{ route('chat.index') }}?target={{$provider->id}}&target-type=ind&account={{auth()->user()->id}}&account-type=user">
                            চ্যাট</a>
                    @endauth
                    @guest()
                        <a class="btn btn-primary fa fa-comments text-white my-2"
                           href="{{ route('login') }}">
                            চ্যাট</a>
                    @endguest
                    @if($provider->facebook)
                        <a class="btn btn-primary fa fa-facebook text-white my-2 facebook-link"
                           target="_blank" href="{{ $provider->facebook }}"> ফেসবুক</a>
                    @endif
                    @if($provider->website)
                        <a class="btn btn-info fa fa-globe text-white my-2" href="{{ $provider->website }}"
                           target="_blank"> ওয়েবসাইট</a>
                    @endif
                    @if($provider->cv)
                        <a class="btn btn-secondary fa fa-file-text text-white my-2"
                           href="{{ 'https://docs.google.com/viewer?url='.asset('storage/'.$provider->cv) }}"
                           target="_blank"> বায়োডাটা</a>
                    @endif

                    <a class="btn btn-warning my-2" href="tel:{{ $provider->mobile }}">
                        <i class="fa fa-phone"></i> {{ en2bnNumber($provider->mobile) }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-3 order-lg-last order-md-1">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header h5 text-center">কর্ম যোগ্যতা সমূহ</div>
                            <div class="card-body">
                                @foreach($provider->subCategories as $subCategory)
                                    <p class="border-bottom font-italic font-weight-bold text-primary">{{ $subCategory->name }}</p>
                                    @foreach($subCategory->workMethods->sortBy('id') as $workMethod)
                                        @if($workMethod->pivot->rate)
                                            @if($workMethod->id != 4)
                                                <p>{{ $workMethod->name }}ঃ {{ en2bnNumber($workMethod->pivot->rate) }}
                                                    টাকা</p>
                                            @else
                                                <p>@if($subCategory->workMethods->count() > 1){{ 'অথবা ' }}@endifচুক্তি
                                                    ভিত্তিক</p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 px-lg-5">
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">নিজের সম্পর্কেঃ</p>
                        <p class="pt-3 text-justify service-description">{{ $provider->description }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="h4 border-bottom">বিস্তারিত ঠিকানাঃ</p>
                        <p>{{ $provider->address }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="h4 border-bottom">কর্ম যোগ্যতার ছবি ও বর্ণনাঃ</p>
                        @forelse($provider->workImages->chunk(2) as $workImages)
                            <div class="card-deck py-2">
                                @foreach($workImages as $image)
                                    <div class="card shadow-sm">
                                        <img class="card-img-top img-fluid" src="{{ asset('storage/'.$image->path) }}"
                                             alt="Card image cap">
                                        <div class="card-body">
                                            <p class="card-text">{{ $image->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            কোন ছবি নেই।
                        @endforelse
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <form class="d-none" id="feedback-delete-form" action="{{ route('feedback.delete') }}"
                              method="post">
                            @method('delete')
                            @csrf()
                        </form>
                        <p class="h4 border-bottom">কমেন্ট সমূহঃ</p>
                        <div class="row">
                            <div class="col-12">
                                @if($canFeedback)
                                    <div class="row">
                                        <div class="col-12">
                                            <form action="{{ route('indFeedback.store') }}"
                                                  method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="feedbackable_id" value="{{ $provider->id }}">
                                                <input id="storeStar" type="number" name="star" required>
                                                <textarea name="say" class="form-control" rows="3"
                                                          placeholder="আপনার মতামত দিন..."></textarea>
                                                <div class="my-2 text-center">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                                <div class="row justify-content-center">
                                    <div class="col-12">
                                        @include('components.success')
                                        @forelse($provider->feedbacks->sortByDesc('id') as $key => $feedback)
                                            <div class="row my-3">
                                                <div class="col-4 col-md-2 col-lg-2 mb-2">
                                                    <img class="img-responsive img-thumbnail"
                                                         src="{{ asset('storage/'.$feedback->user->photo) }}">
                                                </div>
                                                <div class="col-lg-10">
                                                    <input id="showStar{{ $key }}" value="{{ $feedback->star }}"
                                                           class="invisible">
                                                    <p class="mb-0 font-weight-bold">{{ $feedback->user->name }}</p>
                                                    <p>{{ $feedback->say }}</p>
                                                </div>
                                                @if($feedback->user_id == \Illuminate\Support\Facades\Auth::id())
                                                    <div class="col-md-12">
                                                        <button type="submit" name="id" value="{{ $feedback->id }}"
                                                                form="feedback-delete-form"
                                                                class="btn btn-primary">Delete Comment
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <p>কোন কমেন্ট নেই</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Star Rating
        $('#storeStar').rating({
            step: 1,
            size: 'sm',
            showClear: false,
            starCaptions: {
                1: 'এক স্টার',
                2: 'দুই স্টার',
                3: 'তিন স্টার',
                4: 'চার স্টার',
                5: 'পাঁচ স্টার'
            },
            clearCaption: 'কোন স্টার নেই',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            clearButton: '<i class="fa fa-lg fa-minus-circle"></i>'
        })
        $('[id^="showStar"]').rating({
            step: 1,
            size: 'xm',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            showClear: false,
            showCaption: false,
            showCaptionAsTitle: false,
            displayOnly: true
        })

        $('#feedback-delete-form').submit(function () {
            var $confirm = confirm('আপনি কি নিশ্চিত মন্তব্যটি মুছে ফেলতে চান?')
            if (!$confirm) {
                return false
            }

            return true
        })
    </script>
@endsection

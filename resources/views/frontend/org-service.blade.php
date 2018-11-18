@extends('layouts.frontend.master')

@section('title', $provider->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/org-service.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container bg-white shadow">
        <div class="row">
            <img align="left" class="cover" src="{{ asset('storage/seed/user-covers/cover.jpg') }}"/>
        </div>
        <div class="row py-3">
            <div class="col-lg-3 text-center text-lg-right">
                <img class="pp img-thumbnail" src="{{ asset('storage/'.$provider->logo) }}"/>
            </div>
            <div class="col-lg-6">
                <h1 class="text-center text-lg-left mt-3 mt-lg-0">{{ $provider->name }}</h1>
                <p class="h5 text-center text-lg-left mt-3">{{ $provider->category->name }}</p>
                <p class="h5 text-center text-lg-left mt-3 mt-lg-0">{{ $provider->village->bn_name.', '.$provider->union->bn_name.', '.$provider->thana->bn_name.', '.$provider->district->bn_name.', '.$provider->division->bn_name }}</p>
            </div>
            <div class="col-lg-3 pt-3 pl-5 d-none d-lg-inline-block">
                <span class="fa-stack">
                  <i class="fa fa-star fa-stack-2x text-{{ $avgFeedbackColor }}"></i>
                  <i class="fa-stack-1x">{{ en2bnNumber( round($provider->feedbacks_avg, 1) ) }}</i>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-3 order-lg-last">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header h5 text-center">কাজ সমূহ</div>
                            <div class="card-body">
                                @foreach($provider->subCategoryRates->shuffle() as $subCategory)
                                    <p class="border-bottom font-italic">{{ $subCategory->name }}</p>
                                    <p class="sub-cat-price">{{ en2bnNumber( (int) $subCategory->pivot->rate ) }} টাকা</p>
                                @endforeach
                                @foreach($provider->additionalPrices->shuffle() as $additionalPrice)
                                    <p class="border-bottom font-italic">{{ $additionalPrice->name }}</p>
                                    <p class="sub-cat-price">{{ $additionalPrice->info }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 px-5">
                <div class="row my-2">
                    <div class="col-12 text-center">
                        @if($provider->facebook)
                            <a class="btn btn-primary fa fa-facebook text-white my-2" href="{{ $provider->facebook }}" target="_blank"> ফেসবুক</a>
                        @endif
                        @if($provider->website)
                            <a class="btn btn-info fa fa-globe text-white my-2" href="{{ $provider->website }}" target="_blank"> ওয়েবসাইট</a>
                        @endif
                        <span class="btn btn-secondary my-2">
                            <i class="fa fa-phone"></i> {{ en2bnNumber($provider->mobile) }}
                        </span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">প্রতিষ্ঠান সম্পর্কেঃ</p>
                        <p class="pt-3 text-justify">
                            দেশের সর্বস্তরের মানুষের কাছে বিজ্ঞান ও প্রযুক্তি, ফ্রীল্যান্সিং, ইন্টারনেট, ওয়েব ডিজাইন, ওয়েব ডেভেলপমেন্ট বিভিন্ন ধরণের তথ্য প্রকাশের উদ্দেশ্য নিয়ে আমাদের এই ছোট প্রচেষ্টা শুরু করেছিলাম গত ২০১৪ সালের নভেম্বর মাসে। তখন থেকেই আমাদের প্রচেষ্টা কিভাবে মানুষের কাছে লেখা লেখি করে খুব দ্রুত তথ্য প্রদান করা যায়, সেই ভাবেই আমাদের নিরলস প্রচেষ্টায় আজকের “সোর্স টিউন ডট কম”। আমরা এমন কিছু তথ্য প্রদান করার চেষ্টা করছি যাহাতে বাংলাদেশের যুব সমাজ খুব দ্রুত স্বাবলম্বী হতে পারে, তবে আমাদের একার পক্ষে তা সম্ভব নয়, সবার ভালো কোন প্রচেষ্টা থাকলেই সম্ভব এমন উদ্যোগ বাস্তবায়ন করা। আমাদের সবচেয়ে প্রচেষ্টা থাকবে কিভাবে আপনারা অনলাইনে আয় করতে পারেন সেই ধরণের তথ্য আপনাদের নিকট পৌঁছে দেওয়া। এই লক্ষ্য সামনে রেখে আমরা আমাদের প্রচেষ্টাকে আরও বেশী শক্তিশালী করছি।
                        </p>
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
                        <p class="h4 border-bottom">কাজের ছবিঃ</p>
                        @forelse($provider->workImages->shuffle()->chunk(2) as $workImages)
                            <div class="card-deck py-2">
                                @foreach($workImages as $image)
                                    <div class="card shadow-sm">
                                        <img class="card-img-top img-fluid" src="{{ asset('storage/'.$image->path) }}" alt="Card image cap">
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
            </div>
        </div>
        <div class="row mt-4 px-3">
            <div class="col-lg-8">
                <p class="h4 border-bottom">কাজের ফিডব্যাকঃ</p>
                <div class="row">
                    <div class="col-12">
                        @if($canFeedback)
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{ route('org-feedback.store') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="feedbackable_id" value="{{ $provider->id }}">
                                        <input id="storeStar" type="number" name="star" required>
                                        <textarea name="say" class="form-control" rows="3" placeholder="আপনার মতামত দিন..." required></textarea>
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
                                        <div class="col-2">
                                            <img class="img-responsive img-thumbnail" src="{{ asset('storage/'.$feedback->user->photo) }}">
                                        </div>
                                        <div class="col-10">
                                            <input id="showStar{{ $key }}" value="{{ $feedback->star }}" class="invisible">
                                            <p class="mb-0 font-weight-bold">{{ $feedback->user->name }} বলেন:</p>
                                            <p>{{ $feedback->say }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p>কোন মতামত নেই</p>
                                @endforelse
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
                1: 'এক তারা',
                2: 'দুই তারা',
                3: 'তিন তারা',
                4: 'চার তারা',
                5: 'পাঁচ তারা'
            },
            clearButtonTitle: 'মুছে ফেলুন',
            clearCaption: 'কোন তারা নেই',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            clearButton: '<i class="fa fa-lg fa-minus-circle"></i>',
        });
        $('[id^="showStar"]').rating({
            step: 1,
            size: 'xm',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            showClear: false,
            showCaption: false,
            showCaptionAsTitle: false,
            displayOnly: true
        });
    </script>
@endsection
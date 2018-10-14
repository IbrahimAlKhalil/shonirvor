@extends('layouts.frontend.master')

@section('title', $provider->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/ind-service/show.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container bg-white shadow">
        <div class="row">
            <div class="col-12 pp-n-cover">
                <img align="left" class="cover" src="{{ asset('storage/seed/user-covers/cover.jpg') }}"/>
                <img align="left" class="pp img-thumbnail" src="{{ asset('storage/'.$provider->user->photo) }}"/>
                <div class="profile-headline">
                    <h1>{{ $provider->user->name }}</h1>
                    <p class="h5 mt-3">{{ $provider->category->name }}</p>
                    <p class="h5">{{ $provider->village->bn_name.', '.$provider->union->bn_name.', '.$provider->thana->bn_name.', '.$provider->district->bn_name.', '.$provider->division->bn_name }}</p>
                </div>
            </div>
        </div>
        <div class="row py-4">
            <div class="col-8 px-5">
                <div class="row my-2">
                    <div class="col-12 text-center">
                        @if($provider->facebook)
                            <a class="btn btn-primary fa fa-facebook text-white" href="{{ $provider->facebook }}" target="_blank"> ফেসবুক</a>
                        @endif
                        @if($provider->website)
                            <a class="btn btn-info fa fa-globe text-white" href="{{ $provider->website }}" target="_blank"> ওয়েবসাইট</a>
                        @endif
                        <span class="btn btn-secondary">
                            <i class="fa fa-phone"></i> {{ en2bnNumber($provider->mobile) }}
                        </span>
                        <span class="btn btn-secondary">
                            <i class="fa fa-comments"></i> চ্যাট করুন
                        </span>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">নিজের সম্পর্কেঃ</p>
                        <p class="pt-3 text-justify">
                            আমি মোঃ তাওহীদুল ইসলাম। যশোর সরকারী এম.এম কলেজ এর হিসাববিজ্ঞান বিভাগের ২য় বর্ষের ছাত্র। পিতা-মাতার দু্ই সন্তানের মধ্যে আমি বড়। আমার পিতা একজন শিক্ষক। ২০০৭ সালে এস.এস.সি (জিপিএ-৪.৬৯) এবং ২০০৯ সালে এস.এস.সি (জি.পি.এ- ৫.০০)। ফ্রিল্যান্সিং শুরুটা যেভাবেঃ স্কুলে যখন ক্লাস না্ইনে পড়তাম তখন কম্পিঊটার সাবজেক্ট ছিল। বই পড়তাম আর ভাবতাম কি আছে এই জাদুর বাক্সে। যাহোক এস.এস.সি পর্যন্ত আমার কম্পিউটার এর দৌড় এ-পর্যন্ত। এইচ.এস.সি পড়ার সময়কালে কম্পিউটারকে আমি ফোরথ সারজেক্ট হিসাবে নিয়েছিলাম কারণ পরিসংখ্যান বা অংক আমার কাছে কঠিন মনে হত যদিও আমি এ্যাকাউন্টিং-এর ছাত্র। মাঝে মাঝে কম্পিউটার এর ব্যবহারিক ক্লাস হত এবং আমি প্রত্যেকটি ক্লাসে যেতাম অন্যরা কি করে তা দেখার জন্য কারণ কম্পিউটার কিভাবে চালু করতে হয় সেটাও আমি জানতাম না। আমি অবাক হয়ে শুধু দেখতাম আমার বন্ধুরা কিভাবে কোন সুইচটা চাপে। এভাবে সময় শেষ হল এবং পরীক্ষাও শেষ হল শুধু বাকি কশ্পিউটার-এর প্রাকটিক্যাল।
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
                                        <a href="javascript:">
                                            <img class="card-img-top img-fluid" src="{{ asset('storage/'.$image->path) }}" alt="Card image cap">
                                        </a>
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
                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom"> কাজের ফিডব্যাকঃ</p>
                        <div class="row">
                            <div class="col-12">
                                @if($canFeedback)
                                    <div class="row">
                                        <div class="col-12">
                                            <form action="{{ route('ind-feedback.store') }}" method="post">
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
                                        @forelse($provider->feedbacks->shuffle() as $key => $feedback)
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
            <div class="col-4">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header h5 text-center">কাজ সমূহ</div>
                            <div class="card-body">
                                @foreach($provider->subCategories->shuffle() as $subCategory)
                                    <p class="border-bottom font-italic">{{ $subCategory->name }}</p>
                                    @foreach($subCategory->workMethods as $workMethod)
                                        @if($workMethod->id != 4)
                                            <p>{{ $workMethod->name }}ঃ {{ en2bnNumber($workMethod->pivot->rate) }} টাকা</p>
                                        @else
                                            <p>@if($subCategory->workMethods->count() > 1){{ 'অথবা ' }}@endifচুক্তি ভিত্তিক</p>
                                        @endif
                                    @endforeach
                                @endforeach
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
            clearCaption: 'কোন তারা নেই',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>',
            clearButton: '<i class="fa fa-lg fa-minus-circle"></i>'
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















{{--
<div class="row">
    <div class="col-12">
        <p class="lead text-center pt-5">আমার নাম {{ $provider->user->name }}..<br>আমি একজন <mark>বেক্তিগত</mark> সার্ভিস প্রভাইডার...</p>
    </div>
</div>
@if($canFeedback)
    <div class="row py-4 justify-content-center">
        <div class="col-6">
            <p class="h5">আপনার মতামত দিন:</p>
            <form action="{{ route('ind-feedback.store') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="feedbackable_id" value="{{ $provider->id }}">
                <input id="storeStar" type="number" name="star" required>
                <textarea name="say" class="form-control"></textarea>
                <div class="my-2 text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endif
<div class="row py-4 justify-content-center">
    <div class="col-6">
        @include('components.success')
        <p class="h5">মতামত সমূহ:</p>
        @forelse($provider->feedbacks as $key => $feedback)
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
            <p>পূর্বের কোন মতামত নেই</p>
        @endforelse
    </div>
</div>
--}}

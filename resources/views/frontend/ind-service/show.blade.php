@extends('layouts.frontend.master')

@section('title', $provider->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/ind-service/show.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
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
@extends('layouts.backend.master')

@section('title', $provider->user->name)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <p class="lead text-center">আমার নাম {{ $provider->user->name }}..<br>আমি একজন <mark>বেক্তিগত</mark>সার্ভিস প্রভাইডার...</p>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', compact('visitor'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.feedback-counter', compact('countFeedbacks'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
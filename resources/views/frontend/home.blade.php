@extends('layouts.frontend.master')

@section('title', 'হোম')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="text-center">স্বনির্ভর ওয়েবসাইটে আপনাকে স্বাগতম।</h1>
            </div>
        </div>
        <div class="row justify-content-center pt-5">
            <div class="col-6">
                <div class="card shadow">
                    <div class="card-header">
                        <p class="h5">সার্ভিস রেজিস্ট্রেশান</p>
                    </div>
                    <div class="card-body">
                        <p class="card-text">এই ওয়েবসাইটে আপনি আপনার  যেকোনো সার্ভিস (বেক্তিগত কিংবা প্রাতিষ্ঠানিক)-এর বিজ্ঞাপন দিতে পারবেন।</p>
                        <div class="div text-center">
                            <a href="{{ route('service-registration-instruction') }}" class="btn btn-primary">বিস্তারিত পরুন</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.error.master')

@section('title', 'পাওয়া যায়নি')

@section('webpack')
    <script src="{{ asset('assets/js/errors/404.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row align-items-center" style="height: 90vh">
            <div class="col text-center">
                <h1>৪০৪ - পাওয়া যায়নি</h1>
                <p class="lead">আপনি ভুল জায়গায় এসেছেন। আপনি যেই পেজটি খুঁজছেন তা পাওয়া যায় নি।</p>
                <a href="{{ route('home') }}" class="btn btn-secondary">হোম পেজ</a>
            </div>
        </div>
    </div>
@endsection
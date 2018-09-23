@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="@if($indServices->isEmpty() && $orgServices->isEmpty()){{ 'col-12' }}@else{{ 'col-md-9' }}@endif">
                <h1 class="text-center">আপনার ড্যাশবোর্ড</h1>
            </div>
            @if(!$indServices->isEmpty() || !$orgServices->isEmpty())
            <div class="col-md-3">
                <p class="text-center text-muted">Your Services</p>
                @include('components.side-nav', compact('navs'))
            </div>
            @endif
        </div>
    </div>
@endsection
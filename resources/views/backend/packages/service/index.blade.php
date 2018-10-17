@extends('layouts.backend.master')

@section('title', 'সার্ভিস প্যাকেজসমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <h3>সার্ভিস প্যাকেজসমূহ</h3>
            </div>
            <div class="col-md-3">
                <div class="row">
                    @include('components.side-nav')
                </div>
            </div>
        </div>
    </div>
@endsection
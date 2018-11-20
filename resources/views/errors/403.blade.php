@extends('layouts.error.master')

@section('title', 'Not Found')

@section('webpack')
    <script src="{{ asset('assets/js/errors/404.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row align-items-center" style="height: 90vh">
            <div class="col text-center">
                <h1>404 Not Found</h1>
                <p class="lead">The page you requested is not found.</p>
                <a href="{{ route('home') }}" class="btn btn-secondary">Home Page</a>
            </div>
        </div>
    </div>
@endsection
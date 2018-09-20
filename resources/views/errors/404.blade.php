@extends('layouts.error.master')

@section('title', 'Not Found')

@section('webpack')
    <script src="{{ asset('assets/js/error.bundle.js') }}"></script>
@endsection

@section('content')
    <main role="main" class="container">
        <div class="error">
            <h1>404 Not Found</h1>
            <p class="lead">The page you requested is not found.</p>
        </div>
    </main>
@endsection
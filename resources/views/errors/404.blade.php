@extends('layouts.error.master')

@section('title', 'Not Found')

@section('page-css')
    <style>
        .starter-template {
            padding: 3rem 1.5rem;
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <main role="main" class="container">
        <div class="starter-template">
            <h1>404 Not Found</h1>
            <p class="lead">The page you requested is not found.</p>
        </div>
    </main>
@endsection
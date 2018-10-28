@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">আপনার ড্যাশবোর্ড</h1>
            </div>
        </div>
    </div>
@endsection
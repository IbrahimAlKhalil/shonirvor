@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-end">
            <div class="col-12">
                <h1 class="text-center">এডমিন ড্যাশবোর্ড</h1>
            </div>
            <div class="col-4">
                <div class="card text-center">
                    <div class="card-header">SMS  বাকি আছে</div>
                    <div class="card-body">{{ $smsBalance }} টাকার</div>
                </div>
            </div>
        </div>
    </div>
@endsection
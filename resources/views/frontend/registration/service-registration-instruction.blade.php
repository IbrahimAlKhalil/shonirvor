@extends('layouts.frontend.master')

@section('title', 'Service Provider Registration')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-lg-5 bg-white p-4 rounded">
        <div class="row">
            <div class="col-12">
                <h2>সার্ভিস প্রভাইডার এর নিয়মাবলী</h2>
            </div>
            <div class="col-12 mt-4">
                {!! getContent('registration-instruction')->data !!}
            </div>
            <div class="col-12 mt-3">
                <div class="row justify-content-around">
                    <div class="col-lg-6 mb-2 mb-lg-0 text-center text-lg-right">
                        <a href="{{ route('individual-service-registration.index') }}"
                           class="btn btn-secondary btn-success" role="button">বেক্তিগত সার্ভিস রেজিস্ট্রেশান</a>
                    </div>
                    <div class="col-lg-6 text-center text-lg-left">
                        <a href="{{ route('organization-service-registration.index') }}"
                           class="btn btn-secondary btn-success" role="button">প্রাতিষ্ঠানিক সার্ভিস রেজিস্ট্রেশান</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
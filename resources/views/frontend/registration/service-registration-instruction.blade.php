@extends('layouts.frontend.master')

@section('title', 'Service Provider Registration')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2>সার্ভিস প্রভাইডার এর নিয়মাবলী</h2>
            </div>
            <div class="col-12 px-4 mt-4">

            </div>
            <div class="col-12 my-3">
                <div class="row justify-content-around">
                    <div class="col-6 text-right">
                        <a href="{{ route('individual-service-registration.index') }}" class="btn btn-secondary btn-success" role="button">Individual</a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('organization-service-registration.index') }}" class="btn btn-secondary btn-success" role="button">Organization</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
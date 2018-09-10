@extends('layouts.backend.master')

@section('title', $provider->user->name)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <p class="lead text-center">My organization name is {{ $provider->org_name }}..<br> I am a <mark>organization</mark> service provider...</p>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.visitor-conuter', compact('visitor'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
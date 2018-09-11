@extends('layouts.backend.master')

@section('title', $provider->user->name)

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <p class="lead text-center">My name is {{ $provider->user->name }}..<br> I am a <mark>individual</mark> service provider...</p>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', compact('visitor'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
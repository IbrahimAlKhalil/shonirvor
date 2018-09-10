@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="@if($indServices->isEmpty() && $orgServices->isEmpty()){{ 'col-12' }}@else{{ 'col-md-9' }}@endif">
                <h1 class="text-center">Admin Dashboard</h1>
                <p class="lead text-center">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
            </div>
            @if(!$indServices->isEmpty() || !$orgServices->isEmpty())
            <div class="col-md-3">
                <p class="text-center text-muted">Your Services</p>
                @include('components.side-nav', compact('navs'))
            </div>
            @endif
        </div>
    </div>
@endsection
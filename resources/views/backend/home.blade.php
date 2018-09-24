@extends('layouts.backend.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="@if($inds->isEmpty() && $orgs->isEmpty()){{ 'col-12' }}@else{{ 'col-md-9' }}@endif">
                <h1 class="text-center">আপনার ড্যাশবোর্ড</h1>
            </div>
            @if(!$inds->isEmpty() || !$orgs->isEmpty())
                <div class="col-md-3">
                    <p class="text-center text-muted">আপনার পরিষেবাসমূহ</p>
                    @include('components.side-nav', compact('navs'))
                </div>
            @endif
        </div>
    </div>
@endsection
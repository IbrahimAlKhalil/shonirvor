@extends('layouts.frontend.master')

@section('title', $provider->user->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="lead text-center pt-5">My organization name is {{ $provider->org_name }}..<br> I am a <mark>organization</mark> service provider...</p>
            </div>
        </div>
    </div>
@endsection
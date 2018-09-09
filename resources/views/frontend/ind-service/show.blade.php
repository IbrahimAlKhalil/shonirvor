@extends('layouts.frontend.master')

@section('title', $provider->user->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="lead text-center pt-5">My name is {{ $provider->user->name }}..<br> I am a <mark>individual</mark> service provider...</p>
            </div>
        </div>
    </div>
@endsection
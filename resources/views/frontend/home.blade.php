@extends('layouts.frontend.master')

@section('title', 'Home')

@section('content')
    <div class="container my-5">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="text-center">Admin Dashboard</h1>
                <p class="lead text-center">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Dealer Registration</h5>
                        <p class="card-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        <a href="{{ route('dealer.instruction') }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Service Registration</h5>
                        <p class="card-text">This is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content.</p>
                        <a href="{{ route('service-registration-instruction') }}" class="btn btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
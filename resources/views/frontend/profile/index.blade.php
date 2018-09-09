@extends('layouts.frontend.master')

@section('title', $profile->name)

@section('content')
    <div class="container">
        <div class="row my-5 justify-content-md-center">
            <div class="col-md-8">
                <h3 class="mb-3">{{ $profile->name }}</h3>
                @include('components.success')
            </div>
            <div class="w-100"></div>
            <div class="row justify-content-md-center">
                <div class="col-md-3">
                    <img src="{{ asset('storage/'.$profile->photo) }}" class="img-responsive img-thumbnail" alt="Profile Picture">
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <caption>
                            <a href="{{ route('profile.edit', $profile->id) }}">
                                <button class="btn btn-outline-info">Edit</button>
                            </a>
                        </caption>
                        <tbody>
                            <tr>
                                <th>Mobile</th>
                                <td>{{ $profile->mobile }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $profile->email }}</td>
                            </tr>
                            <tr class="border-bottom">
                                <th>Shipping Address</th>
                                <td>{{ $profile->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
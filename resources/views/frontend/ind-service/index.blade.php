@extends('layouts.frontend.master')

@section('title', 'Individual Service Providers')

@section('content')
    <div class="container">
        <div class="row my-5">
            <h3 class="col-12 mb-4">All individual service providers</h3>
            <div class="col-12">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $provider)
                            <tr>
                                <td><a href="{{ route('frontend.ind-service.show', $provider->id) }}">{{ $provider->user->name }}</a></td>
                                <td>{{ $provider->email }}</td>
                                <td>{{ $provider->mobile }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="div mx-auto">
                        {{ $providers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('layouts.backend.master')

@section('title', $dealerRequest->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dealer-request.index') }}">All dealer requests</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row  mt-4">
            <div class="col-md-4">
                <a href="{{ asset('storage/' . $dealerRequest->photo) }}">
                    <img src="{{ asset('storage/' . $dealerRequest->photo) }}" class="img-thumbnail"
                         alt="{{ $dealerRequest->name }}">
                </a>
            </div>

            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th scope="row">Name</th>
                        <td>{{ $dealerRequest->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Mobile</th>
                        <td>{{ $dealerRequest->mobile }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td>{{ $dealerRequest->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $dealerRequest->age }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $dealerRequest->qualification }}</td>
                    </tr>
                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $dealerRequest->nid }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Address</th>
                        <td>{{ $dealerRequest->address }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="btn-group mx-auto" role="group">
                        <a href="{{ route('dealer-request.approve', $dealerRequest->id) }}"
                           class="btn btn-secondary btn-success">Approve</a>
                        <a href="{{ route('dealer-request.reject', $dealerRequest->id) }}"
                           class="btn btn-secondary btn-danger">Reject</a>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="my-4">Documents</h3>

        <div class="row">
            @forelse($dealerRequest->documents as $document)
                <div class="col">
                    <a href="{{ asset('storage/' . $document->document) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->document) }}"
                             class="img-responsive img-rounded img-thumbnail">
                    </a>
                </div>
            @empty
                <div class="col text-muted">No document submitted.</div>
            @endforelse
        </div>
    </div>
@endsection
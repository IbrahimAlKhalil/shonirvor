@extends('layouts.backend.master')

@section('title', $dealerRequest->user->name)

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
                <a href="{{ asset('storage/' . $dealerRequest->user->photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $dealerRequest->user->photo) }}" class="img-thumbnail" alt="{{ $dealerRequest->name }}">
                </a>
            </div>

            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th scope="row">Name</th>
                        <td>{{ $dealerRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Private Mobile</th>
                        <td>{{ $dealerRequest->user->mobile }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Public Mobile</th>
                        <td>{{ $dealerRequest->mobile }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Private Email</th>
                        <td>{{ $dealerRequest->user->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Public Email</th>
                        <td>{{ $dealerRequest->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $dealerRequest->user->age }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $dealerRequest->user->qualification }}</td>
                    </tr>
                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $dealerRequest->user->nid }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Category</th>
                        <td>{{ $dealerRequest->category }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Area</th>
                        <td>
                            @if($dealerRequest->district)
                                {{ $dealerRequest->union. ', ' .$dealerRequest->thana. ', ' .$dealerRequest->district }}.
                            @endif
                            @if($dealerRequest->no_area)
                                <p class="text-danger mb-0">Area not exist.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Private Address</th>
                        <td>{{ $dealerRequest->user->address }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Working Address</th>
                        <td>{{ $dealerRequest->address }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="btn-group mx-auto" role="group">
                        <a href="{{ url('dealer-request/approve') }}" class="btn btn-secondary btn-success rounded-left" onclick="event.preventDefault(); document.getElementById('approve-form').submit();">Approve</a>
                        <form action="{{ route('dealer-request.approve', $dealerRequest->id) }}" method="post" id="approve-form" class="d-none">
                            {{ csrf_field() }}
                        </form>

                        <a href="{{ url('dealer-request/reject') }}" class="btn btn-secondary btn-danger rounded-right" onclick="event.preventDefault(); document.getElementById('reject-form').submit();">Reject</a>
                        <form action="{{ route('dealer-request.reject', $dealerRequest->id) }}" method="post" id="reject-form" class="d-none">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="my-4">Documents</h3>

        <div class="row mb-5">
            @forelse($dealerRequest->documents as $document)
                <div class="col-md-3">
                    <a href="{{ asset('storage/' . $document->path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->path) }}"
                             class="img-responsive img-rounded img-thumbnail">
                    </a>
                </div>
            @empty
                <div class="col text-muted">No document submitted.</div>
            @endforelse
        </div>
    </div>
@endsection
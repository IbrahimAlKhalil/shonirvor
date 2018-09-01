@extends('layouts.backend.master')

@section('title', $serviceRequest->user->name)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('ind-service-request.index') }}">All
                            Service Provider requests</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row  mt-4">
            <div class="col-md-4">
                <a href="{{ asset('storage/' . $serviceRequest->user->photo) }}">
                    <img src="{{ asset('storage/' . $serviceRequest->user->photo) }}" class="img-thumbnail"
                         alt="{{ $serviceRequest->user->name }}">
                </a>
            </div>

            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th scope="row">Name</th>
                        <td>{{ $serviceRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Mobile</th>
                        <td>{{ $serviceRequest->user->mobile }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td>{{ $serviceRequest->email }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $serviceRequest->age }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $serviceRequest->qualification }}</td>
                    </tr>
                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $serviceRequest->nid }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Address</th>
                        <td>{{ $serviceRequest->address }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Latitude</th>
                        <td>{{ $serviceRequest->latitude }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Longitude</th>
                        <td>{{ $serviceRequest->longitude }}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="btn-group mx-auto" role="group">
                        <span class="btn btn-secondary btn-success"
                              onclick="document.getElementById('approve-request').submit();">Approve</span>

                        <span class="btn btn-secondary btn-danger"
                              onclick="document.getElementById('reject-request').submit();">Reject</span>

                        <form id="approve-request" action="{{ route('org-service-request.store') }}"
                              method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                        </form>

                        <form id="reject-request"
                              action="{{ route('org-service-request.destroy', $serviceRequest->id) }}"
                              method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="my-4">Documents</h3>

        <div class="row">
            @forelse($serviceRequest->docs as $document)
                <div class="col">
                    <a href="{{ asset('storage/' . $document->doc) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->doc) }}"
                             class="img-responsive img-rounded img-thumbnail">
                    </a>
                </div>
            @empty
                <div class="col text-muted">No document submitted.</div>
            @endforelse
        </div>

        <h3 class="my-4">Images</h3>

        <div class="row">
            @forelse($serviceRequest->images as $document)
                <div class="col">
                    <a href="{{ asset('storage/' . $document->image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->image) }}"
                             class="img-responsive img-rounded img-thumbnail">
                    </a>
                </div>
            @empty
                <div class="col text-muted">No image submitted.</div>
            @endforelse
        </div>
    </div>
@endsection
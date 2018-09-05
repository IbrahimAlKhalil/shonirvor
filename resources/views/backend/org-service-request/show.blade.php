@extends('layouts.backend.master')

@section('title', $serviceRequest->user->name)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('individual-service-request.index') }}">All
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
                        <th scope="row">User Name</th>
                        <td>{{ $serviceRequest->user->name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Name</th>
                        <td>{{ $serviceRequest->org_name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Mobile</th>
                        <td>{{ $serviceRequest->mobile }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Personal Mobile</th>
                        <td>{{ $serviceRequest->user->mobile }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Working Email</th>
                        <td>{{ $serviceRequest->email }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Personal Email</th>
                        <td>{{ $serviceRequest->user->email }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $serviceRequest->user->age }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $serviceRequest->user->qualification }}</td>
                    </tr>
                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $serviceRequest->user->nid }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Address</th>
                        <td>{{ $serviceRequest->address }}</td>
                    </tr>

                    <tr>
                        <th scope="row">User Address</th>
                        <td>{{ $serviceRequest->user->address }}</td>
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
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $serviceRequest->user->photo) }}">
                            <img src="{{ asset('storage/' . $serviceRequest->user->photo) }}" class="img-thumbnail" alt="{{ $serviceRequest->user->name }}">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $serviceRequest->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                                <tr>
                                    <th scope="row">Mobile</th>
                                    <td>{{ $serviceRequest->mobile }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $serviceRequest->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Age</th>
                                    <td>{{ $serviceRequest->user->age }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Qualification/Experience</th>
                                    <td>{{ $serviceRequest->user->qualification }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">NID</th>
                                    <td>{{ $serviceRequest->user->nid }}</td>
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
                                <span class="btn btn-secondary btn-success" onclick="document.getElementById('approve-request').submit();">Approve</span>
                                <span class="btn btn-secondary btn-danger rounded-right" onclick="document.getElementById('reject-request').submit();">Reject</span>

                                <form id="approve-request" action="{{ route('organization-service-request.store') }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                                </form>

                                <form id="reject-request"
                                      action="{{ route('organization-service-request.destroy', $serviceRequest->id) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="col-12 my-4">Documents</h3>
                    @forelse($serviceRequest->docs as $document)
                        <div class="col">
                            <a href="{{ asset('storage/' . $document->doc) }}" target="_blank">
                                <img src="{{ asset('storage/' . $document->doc) }}"
                                     class="img-responsive img-rounded img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No document submitted.</div>
                    @endforelse
                </div>

                <div class="row">
                    <h3 class="col-12 my-4">Images</h3>
                    @forelse($serviceRequest->images as $document)
                        <div class="col">
                            <a href="{{ asset('storage/' . $document->image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $document->image) }}"
                                     class="img-responsive img-rounded img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No image submitted.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
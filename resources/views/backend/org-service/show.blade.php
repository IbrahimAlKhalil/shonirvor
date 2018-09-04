@extends('layouts.backend.master')

@section('title', $orgService->user->name)

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('individual-service.index') }}">All</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a href="{{ asset('storage/' . $orgService->user->photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $orgService->user->photo) }}" class="img-responsive img-thumbnail"
                         alt="{{ $orgService->user->name }}">
                </a>
            </div>

            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th scope="row">User Name</th>
                        <td>{{ $orgService->user->name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Name</th>
                        <td>{{ $orgService->org_name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Personal Mobile</th>
                        <td>{{ $orgService->user->mobile }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Mobile</th>
                        <td>{{ $orgService->mobile }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Email</th>
                        <td>{{ $orgService->email }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Personal Email</th>
                        <td>{{ $orgService->user->email }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $orgService->user->age }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $orgService->user->qualification }}</td>
                    </tr>

                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $orgService->user->nid }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Organization Address</th>
                        <td>{{ $orgService->address }}</td>
                    </tr>

                    <tr>
                        <th scope="row">User Address</th>
                        <td>{{ $orgService->address }}</td>
                    </tr>

                    </tbody>
                </table>
                <div class="row">
                    {{-- check the account currently showing is deactivated or not --}}

                    @if($orgService->trashed())

                        {{-- current account is deactivated so show "Actiate" button --}}

                        <div class="btn-group mx-auto">
                            <span class="btn btn-secondary btn-success"
                                  onclick="confirm('Are You Sure?') && document.getElementById('activate-account').submit()">Activate</span>
                        </div>
                        <form id="activate-account"
                              action="{{ route('organization-service.activate') }}"
                              method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $orgService->id }}">
                        </form>
                    @else
                        {{-- current account not is deactivated so show "Deactivate" and "Remove" buttons --}}

                        <div class="btn-group mx-auto">
                    <span class="btn btn-secondary btn-warning"
                          onclick="confirm('Are You Sure?') && document.getElementById('deactivate-account').submit()">Deactivate This Account</span>
                            <span class="btn btn-secondary btn-danger"
                                  onclick="confirm('Are You Sure?') && document.getElementById('remove-account').submit()">Remove This Account</span>
                        </div>
                        <form id="deactivate-account"
                              action="{{ route('organization-service.destroy', $orgService->id) }}"
                              method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <input type="hidden" name="type" value="deactivate">
                        </form>
                        <form id="remove-account"
                              action="{{ route('organization-service.destroy', $orgService->id) }}"
                              method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <input type="hidden" name="type" value="remove">
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <h3 class="my-4">Documents</h3>

        <div class="row">
            @forelse($orgService->docs as $document)
                <div class="col-md-3">
                    <a href="{{ asset('storage/' . $document->doc) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->doc) }}" class="img-responsive img-thumbnail">
                    </a>
                </div>
            @empty
                <p class="text-muted">No Document Uploaded!</p>
            @endforelse
        </div>

        <h3 class="my-4">Images</h3>

        <div class="row">
            @forelse($orgService->images as $image)
                <div class="col-md-3">
                    <a href="{{ asset('storage/' . $image->image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $image->image) }}" class="img-responsive img-thumbnail">
                    </a>
                </div>
            @empty
                <p class="text-muted">No Image uploaded!</p>
            @endforelse
        </div>
    </div>
@endsection
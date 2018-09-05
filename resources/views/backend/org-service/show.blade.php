@extends('layouts.backend.master')

@section('title', $orgService->user->name)

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $orgService->user->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $orgService->user->photo) }}" class="img-responsive img-thumbnail" alt="{{ $orgService->user->name }}">
                        </a>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $orgService->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">Mobile</th>
                                <td>{{ $orgService->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td>{{ $orgService->email }}</td>
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
                                <th scope="row">Address</th>
                                <td>{{ $orgService->address }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="btn-group mx-auto">
                                <span class="btn btn-secondary btn-warning"
                                      onclick="confirm('Are You Sure?') && document.getElementById('deactivate-account').submit()">Deactivate This Account</span>
                                <span class="btn btn-secondary btn-danger rounded-right"
                                      onclick="confirm('Are You Sure?') && document.getElementById('remove-account').submit()">Remove This Account</span>

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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">Documents</h3>
                    @forelse($orgService->docs as $document)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $document->doc) }}" target="_blank">
                                <img src="{{ asset('storage/' . $document->doc) }}"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <p class="text-muted col-12">No Document Uploaded!</p>
                    @endforelse
                </div>

                <div class="row">
                        <h3 class="my-4 col-12">Images</h3>
                        @forelse($orgService->images as $image)
                            <div class="col-md-3">
                                <a href="{{ asset('storage/' . $image->image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image->image) }}"
                                         class="img-responsive img-thumbnail">
                                </a>
                            </div>
                        @empty
                            <p class="text-muted col-12">No Image uploaded!</p>
                        @endforelse
                    </div>
            </div>
            <div class="col-md-3 mt-5">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
@extends('layouts.backend.master')

@section('title', $orgService->user->name)

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('ind-service.index') }}">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ind-service.create') }}">Create</a>
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
                        <th scope="row">Name</th>
                        <td>{{ $orgService->user->name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Mobile</th>
                        <td>{{ $orgService->user->mobile }}</td>
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
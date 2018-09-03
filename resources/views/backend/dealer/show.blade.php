@extends('layouts.backend.master')

@section('title', $user->name)

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $user->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $user->photo) }}" class="img-responsive img-thumbnail" alt="{{ $user->name }}">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <h3 class="mb-3">{{ $user->name }}</h3>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">Private Mobile</th>
                                <td>{{ $user->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Public Mobile</th>
                                <td>{{ $user->dealer->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Private Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Public Email</th>
                                <td>{{ $user->dealer->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Age</th>
                                <td>{{ $user->age }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Qualification/Experience</th>
                                <td>{{ $user->qualification }}</td>
                            </tr>
                            <tr>
                                <th scope="row">NID</th>
                                <td>{{ $user->nid }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Category</th>
                                <td>{{ 'Shirt, Pant.' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Area</th>
                                <td>
                                    @if($user->dealer->district)
                                        {{ $user->dealer->union. ', ' .$user->dealer->thana. ', ' .$user->dealer->district }}.
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Private Address</th>
                                <td>{{ $user->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Working Address</th>
                                <td>{{ $user->dealer->address }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="h3 my-4">Documents</div>
                <div class="row">
                    @forelse($user->dealer->documents as $document)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $document->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $document->path) }}" class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No attachments uploaded.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-md-3 mt-5">
                <div class="list-group">
                    <a href="{{ route('dealer.index') }}" class="list-group-item">All Dealer</a>
                    <a href="{{ route('dealer-request.index') }}" class="list-group-item">Dealer Requests</a>
                </div>
            </div>
        </div>
    </div>
@endsection

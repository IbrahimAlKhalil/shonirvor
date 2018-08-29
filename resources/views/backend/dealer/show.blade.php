@extends('layouts.backend.master')

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dealer.index') }}">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dealer.create') }}">Create</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <a href="{{ asset('storage/' . $dealer->photo) }}" target="_blank">
                    <img src="{{ asset('storage/' . $dealer->photo) }}" class="img-responsive img-thumbnail" alt="{{ $dealer->name }}">
                </a>
            </div>

            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <tbody>
                    <tr>
                        <th scope="row">Name</th>
                        <td>{{ $dealer->name }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Mobile</th>
                        <td>{{ $dealer->mobile }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Email</th>
                        <td>{{ $dealer->email }}</td>
                    </tr>


                    <tr>
                        <th scope="row">Age</th>
                        <td>{{ $dealer->age }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Qualification/Experience</th>
                        <td>{{ $dealer->qualification }}</td>
                    </tr>

                    <tr>
                        <th scope="row">NID</th>
                        <td>{{ $dealer->nid }}</td>
                    </tr>

                    <tr>
                        <th scope="row">Address</th>
                        <td>{{ $dealer->address }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="my-4">Documents</h3>

        <div class="row">
            @foreach($dealer->documents as $document)
                <div class="col-md-3">
                    <a href="{{ asset('storage/' . $document->document) }}" target="_blank">
                        <img src="{{ asset('storage/' . $document->document) }}" class="img-responsive img-thumbnail">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection

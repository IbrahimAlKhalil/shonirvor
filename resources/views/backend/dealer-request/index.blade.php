@extends('layouts.backend.master')

@section('content')
    <div class="container">
        <h3 class="my-4">All Requests</h3>
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Mobile</th>
                <th scope="col">Email</th>
            </tr>
            </thead>
            <tbody>
            @forelse($dealerRequests as $dealerRequest)

                @php($serial = $dealerRequests->perPage() * ($dealerRequests->currentPage() - 1) + $loop->iteration)
                <tr>
                    <th scope="row">{{ $serial }}</th>
                    <td><a href="{{ route('dealer-request.show', $dealerRequest->id) }}">{{ $dealerRequest->name }}</a>
                    </td>
                    <td>{{ $dealerRequest->number }}</td>
                    <td>{{ $dealerRequest->email }}</td>
                </tr>

            @empty
                <p class="text-uppercase text-center">No Requests!</p>
            @endforelse
            </tbody>
        </table>

        <div class="row">
            <div class="mx-auto">
                {{ $dealerRequests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
@extends('layouts.backend.master')

@section('title', 'Dealer Requests')

@section('content')
    <div class="container">
        <div class="col">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('dealer.index') }}">All dealer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dealer.create') }}">Create</a>
                </li>
            </ul>
        </div>

        <h3 class="my-4">All Requests</h3>
        @include('components.success')
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
                    <td><a href="{{ route('dealer-request.show', $dealerRequest->id) }}">{{ $dealerRequest->user->name }}</a>
                    </td>
                    <td>{{ $dealerRequest->user->mobile }}</td>
                    <td>{{ $dealerRequest->email }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="4">No Dealer Request Found.</td>
                </tr>
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
@extends('layouts.backend.master')

@section('title', 'Dealer Requests')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 mb-4">
                <h3>All Dealer Requests</h3>
            </div>
            <div class="col-md-9">
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
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('dealer.index') }}" class="list-group-item">All Dealer</a>
                    <a href="{{ route('dealer-request.index') }}" class="list-group-item active">Dealer Requests</a>
                </div>
            </div>
        </div>
    </div>

@endsection
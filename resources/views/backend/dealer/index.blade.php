@extends('layouts.backend.master')

@section('title', 'All Dealers')

@section('content')
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 mb-4">
                <h3>All Dealers</h3>
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
                        @forelse($users as $user)
                            @php($serial = $users->perPage() * ($users->currentPage() - 1) + $loop->iteration)
                            <tr>
                                <td>{{ $serial }}</td>
                                <td><a href="{{ route('dealer.show', $user->id) }}">{{ $user->name }}</a></td>
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Dealer Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $users->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('dealer.index') }}" class="list-group-item active">All Dealer</a>
                    <a href="{{ route('dealer-request.index') }}" class="list-group-item">Dealer Requests</a>
                </div>
            </div>
        </div>
    </div>
@endsection
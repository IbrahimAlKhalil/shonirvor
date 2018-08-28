@extends('layouts.backend.master')

@section('title', 'All Dealers')

@section('content')
    <div class="container">
        <div class="row">
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
            <div class="col">
                <h3 class="mb-4">All Dealers</h3>
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
                        @forelse($dealers as $dealer)
                            @php($serial = $dealers->perPage() * ($dealers->currentPage() - 1) + $loop->iteration)
                            <tr>
                                <td>{{ $serial }}</td>
                                <td><a href="{{ route('dealer.show', $dealer->id) }}">{{ $dealer->name }}</a></td>
                                <td>{{ $dealer->mobile }}</td>
                                <td>{{ $dealer->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Dealer Found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="mx-auto">
                {{ $dealers->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
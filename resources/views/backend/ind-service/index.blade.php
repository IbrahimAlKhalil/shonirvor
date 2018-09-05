@extends('layouts.backend.master')

@section('title', 'All Individual Service Providers')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('ind-service.index') }}" class="btn btn-secondary active">Individual</a>
                <a href="{{ route('org-service.index') }}" class="btn btn-secondary">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">All Individual Service Providers</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
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
                    @forelse($indServices as $indService)
                        @php($serial = $indServices->perPage() * ($indServices->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route('ind-service.show', $indService->id) }}">{{ $indService->user->name }}</a>
                            </td>
                            <td>{{ $indService->mobile }}</td>
                            <td>{{ $indService->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No Service Provider Found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $indServices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
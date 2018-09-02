@extends('layouts.backend.master')

@section('title', 'Organization Service Provider Requests')

@section('content')
    <div class="container">

        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('ind-service-request.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('ind-service-request.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('org-service-request.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('org-service-request.index')){{ 'active' }}@endif">Organization</a>
            </div>
        </div>

        <h3 class="my-4">All Requests</h3>
        @include('components.success')
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Organization Name</th>
                <th scope="col">Email</th>
                <th scope="col">Service</th>
            </tr>
            </thead>
            <tbody>
            @forelse($serviceRequests as $serviceRequest)

                @php($serial = $serviceRequests->perPage() * ($serviceRequests->currentPage() - 1) + $loop->iteration)
                <tr>
                    <th scope="row">{{ $serial }}</th>
                    <td>
                        <a href="{{ route('org-service-request.show', $serviceRequest->id) }}">{{ $serviceRequest->org_name }}</a>
                    </td>
                    <td>{{ $serviceRequest->email }}</td>
                    <td>{{ $serviceRequest->service }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="5"><p class="text-uppercase text-center">Empty!</p></td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div class="row">
            <div class="mx-auto">
                {{ $serviceRequests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
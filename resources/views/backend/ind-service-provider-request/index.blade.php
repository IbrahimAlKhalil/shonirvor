@extends('layouts.backend.master')

@section('title', 'Individual Service Provider Requests')

@section('content')
    <div class="container">

        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('service-provider-request.individual.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('service-provider-request.individual.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('service-provider-request.organization.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('service-provider-request.organization.index')){{ 'active' }}@endif">Organization</a>
            </div>
        </div>

        <h3 class="my-4">All Requests</h3>
        @include('components.success')
        <table class="table table-striped table-bordered table-hover table-sm text-center">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Mobile</th>
                <th scope="col">Age</th>
                <th scope="col">Service</th>
            </tr>
            </thead>
            <tbody>
            @forelse($serviceProviderRequests as $serviceProviderRequest)

                @php($serial = $serviceProviderRequests->perPage() * ($serviceProviderRequests->currentPage() - 1) + $loop->iteration)
                <tr>
                    <th scope="row">{{ $serial }}</th>
                    <td>
                        <a href="{{ route('service-provider-request.individual.show', $serviceProviderRequest->id) }}">{{ $serviceProviderRequest->user->name }}</a>
                    </td>
                    <td>{{ $serviceProviderRequest->user->mobile }}</td>
                    <td>{{ $serviceProviderRequest->age }}</td>
                    <td>{{ $serviceProviderRequest->service }}</td>
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
                {{ $serviceProviderRequests->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

@endsection
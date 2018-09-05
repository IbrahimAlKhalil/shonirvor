@extends('layouts.backend.master')

@section('title', 'Organization Service Requests')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('ind-service-request.index') }}" class="btn btn-secondary">Individual</a>
                <a href="{{ route('org-service-request.index') }}" class="btn btn-secondary active">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">Organization Service Requests</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
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
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>

@endsection
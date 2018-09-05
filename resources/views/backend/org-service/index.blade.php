@extends('layouts.backend.master')

@section('title', 'All Organizational Service Providers')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('individual-service.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('organization-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('organization-service.index')){{ 'active' }}@endif">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">All Organigation Service Providers</h4>
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
                    @forelse($orgServices as $orgService)
                        @php($serial = $orgServices->perPage() * ($orgServices->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route('organization-service.show', $orgService->id) }}">{{ $orgService->org_name }}</a>
                            </td>
                            <td>{{ $orgService->mobile }}</td>
                            <td>{{ $orgService->email }}</td>
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
                        {{ $orgServices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
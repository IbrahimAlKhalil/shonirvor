@extends('layouts.backend.master')

@section('title', 'All Organizational Service Providers')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('organization-service-request.index') }}">Requests</a>
                    </li>
                    {{-- check what if current page showing disable accounts or not --}}

                    @if(route('organization-service.disabled') == request()->url())
                        {{-- current page is showing disable accounts --}}
                        {{-- set the appropriate show route --}}
                        @php($routeToGo = 'organization-service.show-disabled')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization-service.index') }}">Show Activated Accounts
                                Only</a>
                        </li>
                    @else
                        {{-- current page is showing activated accounts --}}
                        {{-- set the appropriate show route --}}
                        @php($routeToGo = 'organization-service.show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('organization-service.disabled') }}">Show Disabled
                                Accounts
                                Only</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('individual-service.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('organization-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('organization-service.index')){{ 'active' }}@endif">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col mt-4">
                <h3 class="mb-4">All Individual Service Providers</h3>
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
                    @forelse($orgServices as $orgService)
                        @php($serial = $orgServices->perPage() * ($orgServices->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route($routeToGo, $orgService->id) }}">{{ $orgService->user->name }}</a>
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
            </div>
        </div>
        <div class="row">
            <div class="mx-auto">
                {{ $orgServices->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
@extends('layouts.backend.master')

@section('title', 'All Individual Service Providers')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ind-service-request.index') }}">Requests</a>
                    </li>

                    {{-- check what if current page showing disable accounts or not --}}

                    @if(route('ind-service.disabled') == request()->url())
                        {{-- current page is showing disable accounts --}}
                        {{-- set the appropriate show route --}}
                        @php($routeToGo = 'ind-service.show-disabled')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ind-service.index') }}">Show Activated Accounts
                                Only</a>
                        </li>
                    @else
                        {{-- current page is showing activated accounts --}}
                        {{-- set the appropriate show route --}}
                        @php($routeToGo = 'ind-service.show')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ind-service.disabled') }}">Show Disabled
                                Accounts
                                Only</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('ind-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('ind-service.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('org-service.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('org-service.index')){{ 'active' }}@endif">Organization</a>
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
                    @forelse($indServices as $indService)
                        @php($serial = $indServices->perPage() * ($indServices->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route($routeToGo, $indService->id) }}">{{ $indService->user->name }}</a>
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
            </div>
        </div>
        <div class="row">
            <div class="mx-auto">
                {{ $indServices->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
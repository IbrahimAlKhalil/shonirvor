@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপনের জন্য আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/request/ad-edit/index.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h5 mb-0">বিজ্ঞাপন জন্য আবেদন</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">প্যাকেজ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($iteration = $applications->perPage() * $applications->currentPage() - $applications->perPage())
                    @forelse($applications as $application)
                        @isset($application->incomeable)
                            <tr>
                                <td>{{ en2bnNumber(++$iteration) }}</td>
                                <td>
                                    <a href="{{ route('backend.request.ad.show', $application->id) }}"
                                       target="_blank">{{ $application->incomeable->user->name }}</a>
                                </td>
                                <td>{{ $application->package->properties->first()->value }}</td>
                            </tr>
                        @endisset
                    @empty
                        <tr>
                            <td colspan="6">
                                কোন আবেদন নেই
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

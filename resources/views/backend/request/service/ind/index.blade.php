@extends('layouts.backend.master')

@section('title', 'সকল বেক্তিগত সার্ভিস রিকোয়েস্ট')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h5 mb-0">বেক্তিগত সার্ভিস রিকোয়েস্ট</li>
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
                        <th scope="col">সার্ভিসের নাম</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($applications as $application)
                        @php($serial = $applications->perPage() * ($applications->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td scope="row">{{ en2bnNumber($serial) }}</td>
                            <td>
                                <a href="{{ route('backend.request.ind-service-request.show', $application->id) }}">{{ $application->user->name }}</a>
                            </td>
                            <td>{{ $application->category->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">কোন সেবা প্রদানকারী খুঁজে পাওয়া যায়নি ।</td>
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
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
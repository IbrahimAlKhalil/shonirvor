@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপন')

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
                        @if(request()->get('type') == 4)
                            <li class="breadcrumb-item active h5 mb-0">প্রাতিষ্ঠানিক টপ সার্ভিস রিকোয়েস্ট</li>
                        @else
                            <li class="breadcrumb-item active h5 mb-0">ব্যক্তিগত টপ সার্ভিস রিকোয়েস্ট</li>
                        @endif
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>নাম</th>
                        <th>প্যাকেজের নাম</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($applications as $key => $application)
                        @php($serial = $applications->perPage() * ($applications->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td class="align-middle">{{ en2bnNumber($serial) }}</td>
                            <td class="align-middle">
                                <a href="{{ route('backend.request.top-service.show', $application->id) }}">{{ $application->name }}</a>
                            </td>
                            <td class="align-middle">{{ $application->package->properties->where('name', 'name')->first()->value }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">কোনো রিকোয়েস্ট নেই।</td>
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
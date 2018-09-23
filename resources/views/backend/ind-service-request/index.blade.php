@extends('layouts.backend.master')

@section('title', 'সকল বেক্তিগত সার্ভিস রিকোয়েস্ট')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service-request.index') }}" class="btn btn-secondary active">বেক্তিগত</a>
                <a href="{{ route('organization-service-request.index') }}" class="btn btn-secondary">প্রাতিষ্ঠানিক</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">সকল বেক্তিগত সার্ভিস রিকোয়েস্ট</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">মোবাইল</th>
                        <th scope="col">বয়স</th>
                        <th scope="col">সার্ভিসের নাম</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($serviceRequests as $serviceRequest)

                        @php($serial = $serviceRequests->perPage() * ($serviceRequests->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <th scope="row">{{ en2bnNumber($serial) }}</th>
                            <td>
                                <a href="{{ route('individual-service-request.show', $serviceRequest->id) }}">{{ $serviceRequest->user->name }}</a>
                            </td>
                            <td>{{ $serviceRequest->mobile }}</td>
                            <td>{{ $serviceRequest->user->age }}</td>
                            <td>{{ $serviceRequest->category->name }}</td>
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
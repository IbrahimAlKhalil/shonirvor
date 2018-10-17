@extends('layouts.backend.master')

@section('title', 'সকল বাতিল প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service.disabled') }}" class="btn btn-secondary">বেক্তিগত</a>
                <a href="{{ route('organization-service.disabled') }}" class="btn btn-secondary active">প্রাতিষ্ঠানিক</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">সকল বাতিল প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">মোবাইল</th>
                        <th scope="col">ইমেইল</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orgs as $org)
                        @php($serial = $orgs->perPage() * ($orgs->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ en2bnNumber($serial)  }}</td>
                            <td>
                                <a href="{{ route('organization-service.show-disabled', $org->id) }}">{{ $org->user->name }}</a>
                            </td>
                            <td>{{ $org->mobile }}</td>
                            <td>{{ $org->email }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">কোন সেবা প্রদানকারী খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $orgs->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
@extends('layouts.backend.master')

@section('title', 'সকল প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service.index') }}" class="btn btn-secondary">ব্যক্তিগত</a>
                <a href="{{ route('organization-service.index') }}" class="btn btn-secondary active">প্রাতিষ্ঠানিক</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">সকল প্রাতিষ্ঠানিক সার্ভিস প্রভাইডার</h4>
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
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route('organization-service.show', $org->id) }}">{{ $org->name }}</a>
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
                        {{ $orgs->links() }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
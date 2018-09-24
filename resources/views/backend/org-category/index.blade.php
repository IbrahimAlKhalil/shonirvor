@extends('layouts.backend.master')

@section('title', 'সকল প্রাতিষ্ঠানিক ক্যাটাগরি')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-category.index') }}" class="btn btn-secondary">Individual</a>
                <a href="{{ route('organization-category.index') }}" class="btn btn-secondary active">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">সকল প্রাতিষ্ঠানিক ক্যাটাগরি</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ক্যাটাগরি</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        @php($serial = $categories->perPage() * ($categories->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ en2bnNumber($serial) }}</td>
                            <td>
                                <a href="{{ route('organization-category.show', $category->id) }}">{{ $category->name }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">কোনো ক্যাটাগরি খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $categories->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
                @include('components.create-ind-category')
            </div>
        </div>
    </div>
@endsection
@extends('layouts.backend.master')

@section('title', 'Individual Service Provider Edit Requests')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-service-edit.index') }}" class="btn btn-secondary active">বেক্তিগত</a>
                <a href="{{ route('organization-service-edit.index') }}" class="btn btn-secondary">প্রাতিষ্ঠানিক</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">বেক্তিগত সার্ভিস প্রভাইডারদের প্রোফাইল আপডেট রিকুয়েস্ট</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">রিকোয়েস্ট এর তারিখ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($serviceEdits as $serviceEdit)
                        @php($serial = $serviceEdits->perPage() * ($serviceEdits->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ en2bnNumber($serial) }}</td>
                            <td>
                                <a href="{{ route('individual-service-edit.show', $serviceEdit->id) }}">{{ $serviceEdit->serviceEditable->user->name }}</a>
                            </td>
                            <td>{{ en2bnNumber($serviceEdit->created_at->format('d/m/y H:i')) }}</td>
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
                        {{ $serviceEdits->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
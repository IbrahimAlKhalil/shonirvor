@extends('layouts.frontend.master')

@section('title', 'সকল বেক্তিগত সার্ভিস প্রভাইডার')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row my-5">
            <h3 class="col-12 mb-4">সকল বেক্তিগত সার্ভিস প্রভাইডার</h3>
            <div class="col-12">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>নাম</th>
                            <th>ইমেইল</th>
                            <th>মোবাইল</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($providers as $provider)
                            <tr>
                                <td><a href="{{ route('frontend.ind-service.show', $provider->id) }}">{{ $provider->user->name }}</a></td>
                                <td>{{ $provider->email }}</td>
                                <td>{{ $provider->mobile }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="row">
                    <div class="div mx-auto">
                        {{ $providers->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
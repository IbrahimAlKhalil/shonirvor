@extends('layouts.backend.master')

@section('title', 'নোটিফিকেশন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <h2>নোটিফিকেশন</h2>
            </div>
        </div>
        <div class="row mt-4 px-5">
            <div class="col-12">
                <table class="table border-bottom">
                    <tbody>
                    @forelse($notifications as $notification)
                        <tr>
                            <td class="@if($notification->read_at){{ 'text-secondary' }}@endif">{{ $notification->data[0] }} @if(!$notification->read_at)<span class="badge badge-info">নতুন</span>@endif</td>
                        </tr>
                        @php($notification->markAsRead())
                    @empty
                        <p class="h5">কোন নোটিফিকেশন নেই</p>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
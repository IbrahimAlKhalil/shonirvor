@extends('layouts.backend.master')

@section('title', 'সার্ভিস রিনিউ আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/request/common.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="bg-white mt-4 p-4 rounded row w-50">
            <div class="col-md-12 mb-3">
                <div class="rounded row shadow-sm">
                    <div class="col-md-12 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="rounded-circle shadow"
                                     style="background-image: url({{ asset('storage/' . $user->photo) }}); width: 100px; height: 100px;"></div>
                            </div>
                            <div class="col-md-9">
                                <div class="w-100 h-100 d-flex align-items-center">
                                    <a href="#">{{ $user->name }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0 list-group mt-4">
                        <table class="table-sm table-striped table-hover">
                            <tr>
                                <th scope="row">প্যাকেজঃ</th>
                                <td>{{ $properties['name'][0]->value  }}</td>
                            </tr>
                            <tr>
                                <th scope="row">পেমেন্ট মেথডঃ</th>
                                <td>{{ $application->paymentMethod->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যে নাম্বার থেকে পাঠানো হয়েছেঃ</th>
                                <td>{{ $application->from }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction ID:</th>
                                <td>{{ $application->transactionId }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="p-2 rounded row shadow-sm d-flex justify-content-center">
                    <div class="btn-group">
                        <button class="btn btn-success" form="approve-form">গ্রহণ করুন</button>
                        <button class="btn btn-danger" form="reject-form">বাতিল করুন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('request.service-renew.update', $application->id) }}" id="approve-form" method="post">
        {{ method_field('put') }}
        {{ csrf_field() }}
    </form>

    <form action="{{ route('request.service-renew.destroy', $application->id) }}" id="reject-form" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection
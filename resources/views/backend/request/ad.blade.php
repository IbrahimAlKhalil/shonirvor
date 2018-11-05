@extends('layouts.backend.master')

@section('title', 'Payments')

@section('webpack')
    <script src="{{ asset('assets/js/backend/common.bundle.js') }}"></script>
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
                        <table class="table-responsive table-sm table-striped table-hover">
                            <tr>
                                <th scope="row">প্যাকেজঃ</th>
                                <td>{{ $properties['name'][0]->value  }}</td>
                            </tr>
                            <tr>
                                <th scope="row">পেমেন্ট মেথডঃ</th>
                                <td>{{ $payment->paymentMethod->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যে নাম্বার থেকে পাঠানো হয়েছেঃ</th>
                                <td>{{ $payment->from }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction ID:</th>
                                <td>{{ $payment->transactionId }}</td>
                            </tr>

                            @if($ad->url)
                                <tr>
                                    <th scope="row">লিঙ্কঃ</th>
                                    <td>{{ $ad->url }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3 p-0">
                <img src="{{ asset('storage/' . $ad->image) }}" class="img-fluid rounded shadow-lg">
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

    <form action="{{ route('request.ad.update', $ad->id) }}" id="approve-form" method="post">
        {{ method_field('put') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{ $payment->id }}" name="payment">
    </form>

    <form action="{{ route('request.ad.destroy', $ad->id) }}" id="reject-form" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
        <input type="hidden" value="{{ $payment->id }}" name="payment">
    </form>
@endsection
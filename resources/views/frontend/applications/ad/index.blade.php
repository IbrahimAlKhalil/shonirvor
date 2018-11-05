@extends('layouts.frontend.master')

@section('title', 'বিজ্ঞাপনের জন্য আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/applications/ad/create.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5 bg-white rounded">
        @if($oldApplication)
            <div class="row p-3 justify-content-center">
                <div class="col-12">
                    <p class="h4">প্রক্রিয়াধীন এপ্লিকেশনঃ</p>
                </div>
                <table class="col-md-8 table table-bordered mt-3">
                    <tbody>
                    <tr>
                        <th>প্যাকেজ</th>
                        <td>{{ $oldApplication->package->properties[0]->value }}</td>
                    </tr>
                    <tr>
                        <th>পেমেন্ট মেথড</th>
                        <td>{{ $oldApplication->paymentMethod->name }}</td>
                    </tr>
                    <tr>
                        <th>যে নাম্বার থেকে টাকা পাঠানো হয়েছে</th>
                        <td>{{ $oldApplication->from }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $oldApplication->transactionId }}</td>
                    </tr>
                    </tbody>
                </table>
                <div class="w-100"></div>
                <div class="col-2 text-center">
                    <a href="{{ route('frontend.applications.ad.edit', $oldApplication->id) }}">
                        <button role="button" class="btn btn-info btn-block">এডিট</button>
                    </a>
                </div>
            </div>
        @else
            <form action="{{ route('frontend.applications.ad.store') }}" class="row p-3 justify-content-center"
                  method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="col-12">
                    <p class="h4">বেক্তিগত টপ সার্ভিস এপ্লিকেশনঃ</p>
                </div>
                <div class="col-8 mt-3">
                    <div class="form-group row">
                        <label for="create-package-select" class="col-md-4 col-form-label text-md-right">প্যাকেজ <span
                                    class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="package" id="create-package-select"
                                    class="form-control{{ $errors->has('package') ? ' is-invalid' : '' }}">
                                <option value="">--প্যাকেজ সিলেক্ট করুন--</option>
                                @foreach($packages as $package)
                                    @php($properties = $package->properties->groupBy('name'))
                                    <option value="{{ $package->id }}"
                                            {{ selectOpt(old('package'), $package->id) }}>
                                        {{ $properties['name'][0]->value }}
                                    </option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'package'])
                        </div>
                    </div>
                </div>
                <div class="col-8 mt-3">
                    <div class="form-group row">
                        <label for="create-method-select" class="col-md-4 col-form-label text-md-right">পেমেন্ট মেথড
                            <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="payment-method" id="create-method-select"
                                    class="form-control{{ $errors->has('payment-method') ? ' is-invalid' : '' }}">
                                <option value="">--পেমেন্ট মেথড সিলেক্ট করুন--</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}"
                                            {{ selectOpt($paymentMethod->id, old('payment-method')) }}>
                                        {{ $paymentMethod->name }}
                                    </option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'payment-method'])
                            @foreach($paymentMethods as $paymentMethod)
                                <div class="text-primary @if(old('payment-method') != $paymentMethod->id){{ 'd-none' }}@endif"
                                     id="create-payment-number-{{ $paymentMethod->id }}">{{ en2bnNumber($paymentMethod->accountId) }}
                                    <i class="text-muted">({{ $paymentMethod->account_type }})</i></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-8 mt-2">
                    <div class="form-group row">
                        <label for="create-from-input" class="col-md-4 col-form-label text-md-right">যে নাম্বার থেকে
                            টাকা পাঠানো হয়েছে <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="from" id="create-from-input"
                                   class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}"
                                   placeholder="কমপক্ষে শেষ ৪ ডিজিট"
                                   value="{{ old('from') }}">
                            @include('components.invalid', ['name' => 'from'])
                        </div>
                    </div>
                </div>
                <div class="col-8 mt-2">
                    <div class="form-group row">
                        <label for="create-transaction-id-input" class="col-md-4 col-form-label text-md-right">Transaction
                            ID <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" id="create-transaction-id-input" name="transaction-id"
                                   class="form-control{{ $errors->has('transaction-id') ? ' is-invalid' : '' }}"
                                   value="{{ old('transaction-id') }}">
                            @include('components.invalid', ['name' => 'transaction-id'])
                        </div>
                    </div>
                </div>
                <div class="col-8 mt-2">
                    <div class="form-group row">
                        <label for="image" class="col-md-4 col-form-label text-md-right">ছবি <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="file" id="image" name="image"
                                   class="file-picker{{ $errors->has('image') ? ' is-invalid' : '' }}"
                                   value="{{ old('image') }}">
                            @include('components.invalid', ['name' => 'image'])
                        </div>
                    </div>
                </div>

                <div class="col-8 mt-2">
                    <div class="form-group row">
                        <label for="url" class="col-md-4 col-form-label text-md-right">লিঙ্ক <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="url" id="url" name="url"
                                   class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                   value="{{ old('url') }}">
                            @include('components.invalid', ['name' => 'url'])
                        </div>
                    </div>
                </div>

                <div class="col-8 mt-3">
                    <div class="row">
                        <span class="col-md-4"></span>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary btn-block w-25 mx-auto">সাবমিট</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
        <div class="row justify-content-center pt-4 px-3 pb-3">
            <div class="col-12">
                <p class="h4">প্যাকেজ সমূহঃ</p>
            </div>
            @foreach($packages as $package)
                @php($properties = $package->properties->groupBy('name'))
                <div class="col-md-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title h5 text-center">{{ $properties['name'][0]->value }}</p>
                            <p class="card-title text-center">{{ readableDays($properties['duration'][0]->value) }}</p>
                            <p class="card-text">{{ $properties['description'][0]->value }}</p>
                        </div>
                        <div class="card-footer text-center font-weight-bold">{{ en2bnNumber($properties['fee'][0]->value) }}
                            টাকা
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
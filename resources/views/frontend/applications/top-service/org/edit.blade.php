@extends('layouts.frontend.master')

@section('title', 'টপ সার্ভিসের আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/applications/top-service/ind.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-lg-5 bg-white rounded">
        <form action="{{ route('frontend.applications.organization-top-service.update', $application->id) }}" class="row p-3 justify-content-center" method="post">
                {{ method_field('put') }}
                {{ csrf_field() }}
                <div class="col-12">
                    <p class="h4">প্রক্রিয়াধীন এপ্লিকেশন এডিটঃ</p>
                </div>
                <div class="col-md-10 col-lg-8 mt-md-3">
                    <div class="form-group row">
                        <label for="service-select" class="col-md-4 col-form-label text-md-right">সার্ভিস <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="service" id="service-select" class="form-control{{ $errors->has('service') ? ' is-invalid' : '' }}">
                                <option value="">--সার্ভিস সিলেক্ট করুন--</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                    @if(old('service') == $service->id
                                    || $application->incomeable->id == $service->id){{ 'selected' }}@endif>
                                        {{ $service->name }}
                                    </option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'service'])
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-8 mt-md-3">
                    <div class="form-group row">
                        <label for="package-select" class="col-md-4 col-form-label text-md-right">প্যাকেজ <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="package" id="package-select" class="form-control{{ $errors->has('package') ? ' is-invalid' : '' }}">
                                <option value="">--প্যাকেজ সিলেক্ট করুন--</option>
                                @foreach($packages as $package)
                                    @php($properties = $package->properties->groupBy('name'))
                                    <option value="{{ $package->id }}"
                                    @if(old('package') == $package->id
                                    || $application->package_id == $package->id){{ 'selected' }}@endif>
                                        {{ $properties['name'][0]->value }}
                                    </option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'package'])
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-8 mt-md-3">
                    <div class="form-group row">
                        <label for="method-select" class="col-md-4 col-form-label text-md-right">পেমেন্ট মেথড <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select name="payment-method" id="method-select" class="form-control{{ $errors->has('payment-method') ? ' is-invalid' : '' }}">
                                <option value="">--পেমেন্ট মেথড সিলেক্ট করুন--</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{ $paymentMethod->id }}"
                                    @if(oldOrData('payment-method', $application->payment_method_id) == $paymentMethod->id){{ 'selected' }}@endif>
                                        {{ $paymentMethod->name }}
                                    </option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'payment-method'])
                            @foreach($paymentMethods as $paymentMethod)
                                <div class="text-primary
                                    @if(oldOrData('payment-method', $application->payment_method_id) != $paymentMethod->id){{ 'd-none' }}@endif"
                                     id="payment-number-{{ $paymentMethod->id }}">
                                    {{ en2bnNumber($paymentMethod->accountId) }} <i class="text-muted">({{ $paymentMethod->account_type }})</i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-8 mt-md-2">
                    <div class="form-group row">
                        <label for="from-input" class="col-md-4 col-form-label text-md-right">যে নাম্বার থেকে টাকা পাঠানো হয়েছে <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" name="from" id="from-input"
                                   class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}"
                                   placeholder="কমপক্ষে শেষ ৪ ডিজিট"
                                   value="{{ oldOrData('from', $application->from) }}">
                            @include('components.invalid', ['name' => 'from'])
                        </div>
                    </div>
                </div>
                <div class="col-md-10 col-lg-8 mt-md-2">
                    <div class="form-group row">
                        <label for="transaction-id-input" class="col-md-4 col-form-label text-md-right">Transaction ID <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input type="text" id="transaction-id-input" name="transaction-id"
                                   class="form-control{{ $errors->has('transaction-id') ? ' is-invalid' : '' }}"
                                   value="{{ oldOrData('transaction-id', $application->transactionId) }}">
                            @include('components.invalid', ['name' => 'transaction-id'])
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary btn-block w-25 mx-auto">সাবমিট</button>
                </div>
            </form>
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
                        <div class="card-footer text-center font-weight-bold">{{ en2bnNumber($properties['fee'][0]->value) }} টাকা</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@extends('layouts.frontend.master')

@section('title', 'টপ সার্ভিসের আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/applications/top-service/ind.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5 bg-white rounded">
        <form action="{{ route('frontend.applications.ind-top-service.store') }}" class="row p-3 justify-content-center" method="post">
            {{ csrf_field() }}
            <div class="col-12">
                <p class="h4"> বেক্তিগত টপ সার্ভিস এপ্লিকেশনঃ</p>
            </div>
            <div class="col-8">
                <div class="form-group row">
                    <label for="create-service-select" class="col-md-4 col-form-label text-right">সার্ভিসঃ</label>
                    <div class="col-md-8">
                        <select name="service" id="create-service-select" class="form-control" required>
                            <option value="">--সার্ভিস সিলেক্ট করুন--</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @if(request()->get('category') == $service->category->id){{ 'selected' }}@endif>{{ $service->category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="w-100"></div>
            <div class="col-8 mt-3">
                <div class="form-group row">
                    <label for="create-package-select" class="col-md-4 col-form-label text-right">প্যাকেজঃ</label>
                    <div class="col-sm-8">
                        <select name="package" id="create-package-select" class="form-control" required>
                            <option value="">--প্যাকেজ সিলেক্ট করুন--</option>
                            @foreach($packages as $package)
                                @php($properties = $package->properties->groupBy('name'))
                                <option value="{{ $package->id }}">{{ $properties['name'][0]->value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-8 mt-3">
                <div class="form-group row">
                    <label for="create-method-select" class="col-md-4 col-form-label text-right">পেমেন্ট মেথডঃ</label>
                    <div class="col-md-8">
                        <select name="payment-method" id="create-method-select" class="form-control" required>
                            <option value="">--পেমেন্ট মেথড সিলেক্ট করুন--</option>
                            @foreach($paymentMethods as $paymentMethod)
                                <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                            @endforeach
                        </select>
                        @foreach($paymentMethods as $paymentMethod)
                            <div class="text-primary d-none" id="create-payment-number-{{ $paymentMethod->id }}">{{ en2bnNumber($paymentMethod->accountId) }} <i class="text-muted">({{ $paymentMethod->account_type }})</i></div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-8 mt-2">
                <div class="form-group row">
                    <label for="create-transaction-id-input" class="col-md-4 col-form-label text-right">Transaction ID:</label>
                    <div class="col-md-8">
                        <input type="text" id="create-transaction-id-input" name="transaction-id" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-block w-25 mx-auto">সাবমিট</button>
            </div>
        </form>
        <div class="row justify-content-center pt-5 px-3 pb-3">
            <div class="col-12">
                <p class="h4">প্যাকেজ সমূহঃ</p>
            </div>
            @foreach($packages as $package)
                @php($properties = $package->properties->groupBy('name'))
                <div class="col-md-4 my-2">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title h5 text-center">{{ $properties['name'][0]->value }}</p>
                            <p class="card-title text-center">{{ en2bnNumber($properties['duration'][0]->value).' দিন' }}</p>
                            <p class="card-text">{{ $properties['description'][0]->value }}</p>
                        </div>
                        <div class="card-footer text-center font-weight-bold">{{ en2bnNumber($properties['fee'][0]->value) }} টাকা</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
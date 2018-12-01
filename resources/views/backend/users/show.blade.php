@extends('layouts.backend.master')

@section('title', 'ইউজার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    @include('components.error')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <h2>{{ $user->name }}</h2>
                <table class="table mt-4 bg-white">
                    <tr>
                        <th>ছবি</th>
                        <td><img src="{{ asset('storage/'.$user->photo) }}" class="w-25"></td>
                    </tr>
                    <tr>
                        <th>মোবাইল</th>
                        <td>{{ $user->mobile }}</td>
                    </tr>
                    <tr>
                        <th>জাতীয় পরিচয়পত্র</th>
                        <td>{{ $user->nid }}</td>
                    </tr>
                    <tr>
                        <th>জন্মতারিখ</th>
                        <td>{{ $user->dob }}</td>
                    </tr>
                    <tr>
                        <th>শিক্ষাগত যোগ্যতা</th>
                        <td>{{ $user->qualification }}</td>
                    </tr>
                    <tr>
                        <th>ঠিকানা</th>
                        <td>{{ $user->address }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.notification')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.sms')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn w-100 @if($user->referPackage()->exists()){{ 'btn-success' }}@else{{ 'btn-info' }}@endif" data-toggle="modal" data-target="#referPackageModal">রেফার প্যাকেজ</button>
                        <!-- Modal -->
                        <div class="modal fade" id="referPackageModal" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">রেফার প্যাকেজ</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('backend.user.refer-package', $user->id) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <div class="modal-body">
                                            <select name="refer-id" class="form-control">
                                                @foreach($referPackages as $package)
                                                    @php($properties = $package->properties->groupBy('name'))
                                                    <option value="{{ $package->id }}"
                                                        @if($package->id == $userReferPackageId){{ 'selected' }}@endif>
                                                        {{ $properties['name'][0]->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer  border-top-0">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary">সাবমিট</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header text-center">রেফারেন্স</div>
                            <div class="card-body">
                                @if($user->references->isNotEmpty())
                                    <p>পাওনাঃ<span class="float-right">{{ en2bnNumber(round($payable, 2)) }} টাকা</span></p>
                                    <p>মোট উপার্জনঃ<span class="float-right">{{ en2bnNumber(round($totalEarn, 2)) }} টাকা</span></p>
                                    <button type="button" href="javascript:" class="btn btn-info w-100" data-toggle="modal" data-target="#referrencePaymentModal">পেমেন্ট করুন</button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="referrencePaymentModal" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">রেফারেন্স পেমেন্ট</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('backend.user.pay-referrer', $user->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('put') }}
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @if($user->paymentReceiveMethod)
                                                                <div class="col-md-6">
                                                                    টাকা গ্রহণের মাধ্যম
                                                                </div>
                                                                <div class="col-md-6">
                                                                    {{ $user->paymentReceiveMethod->number }} ({{ $user->paymentReceiveMethod->type }})
                                                                </div>
                                                            @else
                                                                <div class="col-12 text-center text-danger">
                                                                    এই ইউজার কোন পেমেন্ট গ্রহণের মাধ্যম দেয় নি।
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @if($user->paymentReceiveMethod)
                                                            <hr>
                                                            <div class="form-group">
                                                                <label for="amount">টাকার পরিমাণ
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="number" name="amount" id="amount" class="form-control" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="method-select">পেমেন্ট মেথড
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <select name="method" id="method-select" class="form-control">
                                                                    <option value="">-- মেথড সিলেক্ট করুন --</option>
                                                                    @foreach($paymentMethods as $method)
                                                                        <option value="{{ $method->id }}">{{ $method->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="send-from">যে নাম্বার থেকে টাকা পাঠানো হয়েছে
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="number" name="from" id="send-from" class="form-control" placeholder="কমপক্ষে শেষ ৪ ডিজিট" autocomplete="off">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="transaction-id-input">Transaction ID
                                                                    <span class="text-danger">*</span>
                                                                </label>
                                                                <input type="text" id="transaction-id-input" name="transaction-id" class="form-control" autocomplete="off">
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer  border-top-0">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                                        @if($user->paymentReceiveMethod)
                                                            <button type="submit" class="btn btn-primary">সাবমিট</button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @foreach($user->references as $reference)
                                        @if($reference->service_type_id == 1)
                                            <p class="text-center">
                                                @if($reference->service->deleted_at == null)
                                                    <a href="{{ route('individual-service.show', $reference->service->id) }}" target="_blank">{{ $reference->service->user->name }}</a>
                                                @else
                                                    <a href="{{ route('individual-service.show-disabled', $reference->service->id) }}" target="_blank">{{ $reference->service->user->name }}</a>
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-center">
                                                @if($reference->service->deleted_at == null)
                                                    <a href="{{ route('organization-service.show', $reference->service->id) }}" target="_blank">{{ $reference->service->name }}</a>
                                                @else
                                                    <a href="{{ route('organization-service.show-disabled', $reference->service->id) }}" target="_blank">{{ $reference->service->name }}</a>
                                                @endif
                                            </p>
                                        @endif
                                    @endforeach
                                @else
                                    <p class="text-center">এই ইউজার এখনো কাউকে রেফার করে নি।</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
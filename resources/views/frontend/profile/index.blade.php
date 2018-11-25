@extends('layouts.frontend.master')

@section('title', $user->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-lg-5 py-4 bg-white rounded">
        <div class="row justify-content-center">
            <div class="col-lg-3 text-center">
                <h3>{{ $user->name }}</h3>
                <img src="{{ asset('storage/'.$user->photo) }}" class="img-responsive img-thumbnail" alt="Profile Picture">
            </div>
            <div class="col-lg-6">
                <h3 class="invisible">{{ $user->name }}</h3>
                <table class="table table-hover mb-0">
                    <caption class="text-center">
                        <a href="{{ route('profile.edit', $user->id) }}">
                            <button class="btn btn-info">Edit</button>
                        </a>
                    </caption>
                    <tbody>
                        <tr>
                            <th>মোবাইল নাম্বার</th>
                            <td>{{ $user->mobile }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <th>ঠিকানা</th>
                            <td>{{ $user->address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-3 text-center mt-3 mt-lg-0">
                <p class="h4"><span class="border-bottom">নোটিফিকেশন</span></p>
                <div style="overflow-y: scroll; max-height: 175px">
                    @forelse($notifications as $notification)
                        <p>&diams; {{ $notification->data[0] }}</p>
                    @empty
                        <p>কোন নোটিফিকেশন নেই।</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 h4 text-center mt-5 mb-3">
                <span class="border-bottom">রেফারেন্স</span>
            </div>
            <div class="col-lg-4 order-lg-last">
                <div class="card">
                    <div class="card-header text-center">ইনকাম</div>
                    <div class="card-body">
                        <p>পাওনাঃ<span class="float-right">{{ en2bnNumber(round($payable, 2)) }} টাকা</span></p>
                        <p>মোট উপার্জনঃ<span class="float-right">{{ en2bnNumber(round($totalEarn, 2)) }} টাকা</span></p>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header text-center">ইনকাম গ্রহণের মাধ্যম</div>
                    <div class="card-body">
                        <form action="{{ route('profile.payment-receive-method', $user->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="payment-receive-type" class="pl-1">ধরনঃ</label>
                                <select name="type" id="payment-receive-type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    <option value="">-- একাউন্টের ধরন নির্বাচন করুন --</option>
                                    <option value="bkash" @if(oldOrData('type', $user->paymentReceiveMethod ? $user->paymentReceiveMethod->type : '') == 'bkash'){{ 'selected' }}@endif>বিকাশ (পারসোনাল)</option>
                                    <option value="rocket" @if(oldOrData('type', $user->paymentReceiveMethod ? $user->paymentReceiveMethod->type : '') == 'rocket'){{ 'selected' }}@endif>রকেট (পারসোনাল)</option>
                                </select>
                                @include('components.invalid', ['name' => 'type'])
                            </div>
                            <div class="form-group">
                                <label for="payment-receive-number" class="pl-1">মোবাইলঃ</label>
                                <input type="text" name="number" id="payment-receive-number" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" placeholder="01xxxxxxxxx" value="{{ oldOrData('munber', $user->paymentReceiveMethod ? $user->paymentReceiveMethod->number : '') }}">
                                @include('components.invalid', ['name' => 'number'])
                            </div>
                            <button type="submit" class="btn btn-info d-block mx-auto">আপডেট</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header text-center">প্যাকেজ</div>
                    <ul class="list-group list-group-flush">
                        @if($referPackage['refer_target'][0]->value && $referPackage['duration'][0]->value)
                            <li class="list-group-item">
                                টার্গেটঃ<span class="float-right">
                                {{ en2bnNumber($referPackage['refer_target'][0]->value) }} টি
                                </span>
                            </li>
                            <li class="list-group-item">
                                সময় বাকি আছেঃ<span class="float-right">
                                    {{ readableDays($user->referPackage->created_at->addDays($referPackage['duration'][0]->value)->diffInDays(now())) }}
                                </span>
                            </li>
                        @endif
                        @if($referPackage['refer_onetime_interest'][0]->value)
                            <li class="list-group-item">
                                রেজিস্ট্রেশন ইন্টারেস্টঃ<span class="float-right">
                                    {{ en2bnNumber($referPackage['refer_onetime_interest'][0]->value) }}%
                                </span>
                            </li>
                        @endif
                        @if($referPackage['refer_renew_interest'][0]->value)
                            <li class="list-group-item">
                                রিনিউ ইন্টারেস্টঃ<span class="float-right">
                                {{ en2bnNumber($referPackage['refer_renew_interest'][0]->value) }}%
                                </span>
                            </li>
                        @endif
                        @if($referPackage['refer_target'][0]->value && $referPackage['duration'][0]->value)
                            @if($referPackage['refer_fail_onetime_interest'][0]->value)
                                <li class="list-group-item">
                                    টার্গেটে ব্যর্থ হলে রেজিস্ট্রেশন ইন্টারেস্টঃ<span class="float-right">
                                        {{ en2bnNumber($referPackage['refer_fail_onetime_interest'][0]->value) }}%
                                    </span>
                                </li>
                            @endif
                            @if($referPackage['refer_fail_renew_interest'][0]->value)
                                <li class="list-group-item">
                                    টার্গেটে ব্যর্থ হলে রিনিউ ইন্টারেস্টঃ<span class="float-right">
                                        {{ en2bnNumber($referPackage['refer_fail_renew_interest'][0]->value) }}%
                                    </span>
                                </li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 h5 text-center pt-4">
                <div class="card-columns">
                    @foreach($references as $reference)
                        @php($serviceType = (new ReflectionClass($reference->service))->getShortName())
                        <div class="card">
                            @if($serviceType == 'Ind')
                                <img class="card-img-top" src="{{ asset('storage/'.$reference->service->user->photo) }}">
                            @else
                                <img class="card-img-top" src="{{ asset('storage/'.$reference->service->logo) }}">
                            @endif
                            <div class="card-body">
                                @if($serviceType == 'Ind')
                                    @if($reference->service->deleted_at == null)
                                        <a class="h4 card-title text-truncate d-block" href="{{ route('frontend.ind-service.show', $reference->service->slug) }}">{{ $reference->service->user->name }}</a>
                                    @else
                                        <p class="h4 card-title text-truncate">{{ $reference->service->user->name }}</p>
                                    @endif
                                @else
                                    @if($reference->service->deleted_at == null)
                                        <a class="h4 card-title text-truncate d-block" href="{{ route('frontend.org-service.show', $reference->service->slug) }}">{{ $reference->service->name }}</a>
                                    @else
                                        <p class="h4 card-title text-truncate">{{ $reference->service->name }}</p>
                                    @endif
                                @endif
                                <p class="card-text"><i>{{ $reference->service->category->name }}</i></p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($references->isEmpty())
                    <p>আপনি এখনো কাউকে রেফার করেন নি এবং কোন উপার্জন করেন নি। আমাদের ওয়েবসাইটের এফিলিয়েট প্রোগ্রামের মাদ্ধমে আপনি অর্থ উপার্জন করতে পারেন।</p>
                @endif
            </div>
        </div>
    </div>
@endsection
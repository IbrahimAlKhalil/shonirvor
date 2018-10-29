@extends('layouts.frontend.master')

@section('title', 'সার্ভিস সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9 bg-white rounded p-4">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('storage/' . $service->logo) }}"
                             alt="{{ $service->name }}" class="img-thumbnail w-100">
                    </div>

                    <div class="col-md-7 d-flex flex-column flex-wrap justify-content-end">
                        <h1>{{ $service->name }}</h1>
                        <p class="h5">{{ $service->category->name }}</p>
                        <p class="h5">{{ $service->village->bn_name.', '.$service->union->bn_name.', '.$service->thana->bn_name.', '.$service->district->bn_name.', '.$service->division->bn_name }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">বর্ণনাঃ</p>
                        <p class="pt-3 text-justify">
                            {{ $service->description }}
                        </p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সাধারণ তথ্যঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                                <tr>
                                    <th scope="row">মোবাইল নম্বর</th>
                                    <td>{{ $service->mobile }}</td>
                                </tr>
                                @if($service->referredBy)
                                    <tr>
                                        <th scope="row">রেফারার</th>
                                        <td>
                                            <a href="javascript:">{{ $service->referredBy->user->name }}</a>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="row">ইমেইল</th>
                                    <td>{{ $service->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">ওয়েবসাইট</th>
                                    <td>{{ $service->website }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">ফেসবুক</th>
                                    <td>{{ $service->facebook }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                    <td>{{ $service->user->nid }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">ঠিকানাঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100 th-w-15">
                            <tbody>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $service->district->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">থানা</th>
                                <td>{{ $service->thana->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">ইউনিয়ন</th>
                                <td>{{ $service->union->bn_name }}</td>
                            </tr>

                            <tr>
                                <th scope="row">এলাকা</th>
                                <td>{{ $service->village->bn_name }}</td>
                            </tr>

                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $service->address }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সার্ভিস ক্যাটাগরিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                                <tr>
                                    <th scope="row">ক্যাটাগরি</th>
                                    <td>{{ $service->category->name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সার্ভিস সমূহঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100 text-center">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">নাম</th>
                                <th scope="col">মূল্য</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($service->subCategoryRates as $index => $subCategory)
                                <tr>
                                    <td> {{ en2bnNumber($index+1) }} </td>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ en2bnNumber($subCategory->pivot->rate) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td><span class="text-muted small">কোন সার্ভিস নেই</span></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">ডকুমেন্টঃ</p>
                        <div class="row">
                            @if($service->trade_license)
                                <div class="col-md-3">
                                    <span class="text-muted">ট্রেড লাইসেন্স</span>
                                    <a href="{{ asset('storage/' . $service->trade_license) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $service->trade_license) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @foreach($service->user->identities as $identity)
                                <div class="col-md-3">
                                    <a href="{{ asset('storage/' . $identity->path) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $identity->path) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endforeach
                            @if( ! $service->user->identities->first()
                                && ! $service->trade_license
                                && ! $service->user->identities->first())
                                <p class="text-muted col-12">কোন ডকুমেন্ট আপলোড করা হয়নি!</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">কাজের ছবিঃ</p>
                        <div class="row">
                            <div class="col-12">
                                @forelse($service->workImages->chunk(2) as $chunk)
                                    <div class="card-deck py-2">
                                        @foreach($chunk as $image)
                                            <div class="card shadow-sm">
                                                <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                                    <img class="card-img-top img-fluid"
                                                         src="{{ asset('storage/' . $image->path) }}">
                                                </a>
                                                <div class="card-body">
                                                    <p class="card-text">{{ $image->description }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="text-muted col-12">কোন ছবি আপলোড করা হয়নি!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', ['visitor' => orgVisitorCount($service->id)])
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn btn-info btn-block">টপ সার্ভিসের জন্য আবেদন করুন</button>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn btn-info btn-block">প্রোফাইলটি এডিট করুন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
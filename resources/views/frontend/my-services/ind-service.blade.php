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
                        <img src="{{ asset('storage/' . $service->user->photo) }}"
                             alt="{{ $service->user->name }}" class="img-thumbnail w-100">
                    </div>

                    <div class="col-md-7 d-flex flex-column flex-wrap justify-content-end">
                        <h1>{{ $service->user->name }}</h1>
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
                                <th scope="row">জন্ম তারিখ</th>
                                <td>{{ implode('-', array_reverse(explode('-', $service->user->dob))) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যোগ্যতা/অভিজ্ঞতা</th>
                                <td>{{ $service->user->qualification }}</td>
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
                                <th>#</th>
                                <th>নাম</th>
                                @foreach($workMethods as $workMethod)
                                    <th>{{ $workMethod->name }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($service->subCategories as $index => $subCategory)
                                <tr>
                                    <td> {{ en2bnNumber($index+1) }} </td>
                                    <td>{{ $subCategory->name }}</td>
                                    @php($methods = $indWorkMethods[$subCategory->id])
                                    @php($methodIds = $methods->pluck('id')->toArray())
                                    @foreach($workMethods as $method)
                                        @if($method->id != 4)
                                            @php($currentMethod = $methods->filter(function($item)use($method){return $item->id == $method->id;}))
                                            <td>
                                                @if($currentMethod->first())
                                                    ৳{{ en2bnNumber($currentMethod->first()->pivot->rate) }}
                                                @else
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                @if(in_array(4, $methodIds))
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-times" aria-hidden="true"></i>
                                                @endif
                                            </td>
                                        @endif
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td><span class="text-muted small">কোন সাব-ক্যাটাগরি নেই</span></td>
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
                            @if($service->experience_certificate)
                                <div class="col-md-3">
                                    <span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>
                                    <a href="{{ asset('storage/' . $service->experience_certificate) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $service->experience_certificate) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @if($service->cv)
                                <div class="col-md-3">
                                    <span class="text-muted">বায়োডাটা</span>
                                    <a href="{{ asset('storage/' . $service->cv) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $service->cv) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @if( ! $service->experience_certificate
                                && ! $service->cv)
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
                        @include('components.visitor-conuter', ['visitor' => indVisitorCount($service->id)])
                    </div>
                </div>
                @if($service->deleted_at == null)
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('frontend.applications.individual-top-service.index').'?category='.$service->category_id }}">
                                <button type="button" class="btn btn-info btn-block">টপ সার্ভিসের জন্য আবেদন করুন
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12">
                        <a type="button" target="_blank" href="{{ route('frontend.my-service.ind.edit', $service->id) }}"
                           class="btn btn-info btn-block">প্রোফাইলটি এডিট করুন</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
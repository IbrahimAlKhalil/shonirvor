@extends('layouts.frontend.master')

@section('title', 'সার্ভিস সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row d-block d-lg-none mb-4">
            <div class="col-12 px-md-0">
                @include('components.side-nav')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-9 bg-white rounded p-4">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('storage/' . $service->user->photo) }}" class="img-thumbnail">
                    </div>
                    <div class="col-md-9 d-flex flex-column flex-wrap justify-content-end">
                        <h1 class="mt-3 mt-md-0 text-center">{{ $service->user->name }}</h1>
                        <p class="h5 mt-2 mt-md-0 text-center">{{ $service->category->name }}</p>
                        <p class="h5 text-center">{{ $service->village->bn_name.', '.$service->union->bn_name.', '.$service->thana->bn_name.', '.$service->district->bn_name.', '.$service->division->bn_name }}</p>
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
                            @if($service->user->dob)
                                <tr>
                                    <th scope="row">জন্ম তারিখ</th>
                                    <td>{{ implode('-', array_reverse(explode('-', $service->user->dob))) }}</td>
                                </tr>
                            @endif
                            @if($service->user->qualification)
                                <tr>
                                    <th scope="row">যোগ্যতা/অভিজ্ঞতা</th>
                                    <td>{{ $service->user->qualification }}</td>
                                </tr>
                            @endif
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
                                    @if(isset($indWorkMethods[$subCategory->id]))
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
                                    @else
                                        <td><i class="fa fa-times" aria-hidden="true"></i></td>
                                        <td><i class="fa fa-times" aria-hidden="true"></i></td>
                                        <td><i class="fa fa-times" aria-hidden="true"></i></td>
                                        <td><i class="fa fa-times" aria-hidden="true"></i></td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"><span class="text-muted small">কোন সাব-ক্যাটাগরি নেই</span></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12 px-0 mt-4">
                    <p class="h4 border-bottom">ডকুমেন্টঃ</p>
                    <div class="row">
                        @if($service->cv)
                            <div class="col-lg-3 mt-2 mb-3 mb-lg-0">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <span class="text-muted">বায়োডাটা</span>
                                    </div>
                                    <div class="card-body text-center">
                                        <a href="{{ asset('storage/' . $service->cv) }}" target="_blank">
                                            <img src="{{ asset('storage/default/icons/pdf.svg') }}"
                                                 class="img-fluid img-thumbnail rounded">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($service->experience_certificate)
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ asset('storage/' . $service->experience_certificate) }}"
                                           target="_blank">
                                            <img class="img-thumbnail img-fluid rounded"
                                                 src="{{ asset('storage/' . $service->experience_certificate) }}">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                <span class="text-muted">
                                    জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ
                                </span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($service->user->identities as $identity)
                                            <div class="col">
                                                <a href="{{ asset('storage/' . $identity->path) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $identity->path) }}"
                                                         class="img-fluid img-thumbnail rounded">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if( ! $service->experience_certificate && ! $service->cv && !$service->user->identities->first())
                            <p class="text-muted col-12">কোন ডকুমেন্ট আপলোড করা হয়নি!</p>
                        @endif
                    </div>
                </div>
                <div class="col-12 px-0 mt-4">
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
            <div class="col-lg-3">
                <div class="row d-none d-lg-block">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', ['visitor' => indVisitorCount($service->id)])
                    </div>
                </div>
                @if($service->deleted_at == null && $service->expire != null)
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('frontend.applications.individual-top-service.index').'?category='.$service->category_id }}"
                               style="text-decoration-line: none">
                                <button type="button" class="btn btn-info btn-block">টপ সার্ভিসের জন্য আবেদন করুন
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12">
                        @if($service->expire != null)
                            @if(!$editExists)
                                <a class="text-white" href="{{ route('frontend.my-service.ind.edit', $service->id) }}"
                                   style="text-decoration-line: none">
                                    <button type="button" class="btn btn-info btn-block">সার্ভিসটি এডিট করুন</button>
                                </a>
                            @else
                                <div class="modal fade" id="warning" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body text-center">
                                                আপনার একটি এডিট প্রক্রিয়াধীন রয়েছে। এডমিন আপনার পূর্বের আবেদনটি
                                                গ্রহণ করলে পুনরায় আবেদন করতে পারবেন।
                                            </div>
                                            <div class="modal-footer border-top-0 row justify-content-center">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info btn-block" data-toggle="modal"
                                        data-target="#warning">সার্ভিসটি এডিট করুন
                                </button>
                            @endif
                        @else
                            <a class="text-white"
                               href="{{ route('individual-service-registration.edit', $service->id) }}"
                               style="text-decoration-line: none">
                                <button type="button" class="btn btn-info btn-block">সার্ভিসটি এডিট করুন</button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
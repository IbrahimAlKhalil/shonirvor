@extends('layouts.backend.master')

@section('title', $application->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/backend/ind-service-request/show.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <form class="col-md-9 bg-white py-3" id="approve-request"
                  action="{{ route('backend.request.ind-service-request.update', $application->id) }}"
                  method="post">
                {{ method_field('put') }}
                {{ csrf_field() }}
                <div class="row">
                    <div class="row col-12">
                        <div class="col-md-3">
                            <img src="{{ asset('storage/' . $application->user->photo) }}"
                                 alt="{{ $application->user->name }}" class="img-thumbnail w-100">
                        </div>

                        <div class="col-md-7 d-flex flex-column flex-wrap justify-content-end">
                            <h1>{{ $application->user->name }}</h1>
                            <p class="h5">{{ $application->category->name }}</p>
                            <p class="h5">{{ $application->village->bn_name.', '.$application->union->bn_name.', '.$application->thana->bn_name.', '.$application->district->bn_name.', '.$application->division->bn_name }}</p>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">বর্ণনাঃ</p>
                        <p class="pt-3 text-justify">
                            {{ $application->description }}
                        </p>
                    </div>

                    @if($application->payments->first())
                        @php($payment = $application->payments->first())
                        <div class="col-12 mt-4">
                            <p class="h4 border-bottom">প্যাকেজ এবং টাকা প্রদানের অবস্থাঃ</p>
                            <table class="table table-striped table-bordered table-hover table-sm w-100">
                                <tbody>
                                <tr>
                                    <th scope="row"><label for="package">প্যাকেজের নামঃ</label></th>
                                    <td>
                                        <input type="hidden" value="{{ $payment->id }}" name="payment">
                                        <select name="package" id="package">
                                            @foreach($packages as $package)
                                                @php($properties = $package->properties->groupBy('name'))
                                                <option value="{{ $package->id }}" {{ selectOpt($package->id, $payment->package->id) }}>{{ $properties['name'][0]->value }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">পেমেন্ট এর মাধ্যম</th>
                                    <td>{{ $payment->paymentMethod->name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row"> Transaction ID:</th>
                                    <td>{{ $payment->transactionId }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">সাধারণ তথ্যঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row">মোবাইল নম্বর</th>
                                <td>{{ $application->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">রেফারার</th>
                                <td>
                                    <a href="javascript:">{{ $application->referredBy ? $application->referredBy->user->name : '' }}</a>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">ইমেইল</th>
                                <td>{{ $application->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ওয়েবসাইট</th>
                                <td>{{ $application->website }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ফেসবুক</th>
                                <td>{{ $application->facebook }}</td>
                            </tr>
                            <tr>
                                <th scope="row">বয়স</th>
                                <td>{{ $application->user->age }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যোগ্যতা/অভিজ্ঞতা</th>
                                <td>{{ $application->user->qualification }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $application->user->nid }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">ঠিকানাঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100 th-w-15">
                            <tbody>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $application->district->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="thana-request">থানা</label></th>
                                <td>
                                    @if($application->thana->is_pending)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input name="thana-request" id="thana-request" type="text"
                                                       class="form-control"
                                                       value="{{ $application->thana->bn_name}}">
                                            </div>
                                            <div class="col-md-6">
                                                <select name="thana" id="thana"
                                                        data-placeholder="-- থানা --"
                                                        data-option-loader-url="{{ route('api.unions') }}"
                                                        data-option-loader-target="#union"
                                                        data-option-loader-param="thana">
                                                    <option value="">-- থানা --</option>
                                                    @foreach($thanas as $thana)
                                                        <option value="{{ $thana->id }}">{{ $thana->bn_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        {{ $application->thana->bn_name}}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="union-request">ইউনিয়ন</label></th>
                                <td>
                                    @if($application->union->is_pending)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="union-request" name="union-request" type="text"
                                                       class="form-control"
                                                       value="{{ $application->union->bn_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <select name="union" id="union"
                                                        data-placeholder="-- ইউনিয়ন --"
                                                        data-option-loader-url="{{ route('api.villages') }}"
                                                        data-option-loader-target="#village"
                                                        data-option-loader-param="union"
                                                        data-option-loader-properties="value=id,text=bn_name">
                                                    <option value="">-- ইউনিয়ন --</option>
                                                    @if($unions)
                                                        @foreach($unions as $union)
                                                            <option value="{{ $union->id }}">{{ $union->bn_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        {{ $application->union->bn_name }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="village-request">এলাকা</label></th>
                                <td>
                                    @if($application->village->is_pending)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="village-request" name="village-request" type="text"
                                                       class="form-control"
                                                       value="{{ $application->village->bn_name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <select name="village" id="village"
                                                        data-placeholder="-- এলাকা --"
                                                        data-option-loader-properties="value=id,text=bn_name">
                                                    <option value="">-- এলাকা --</option>
                                                    @if($villages)
                                                        @foreach($villages as $village)
                                                            <option value="{{ $village->id }}">{{ $village->bn_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        {{ $application->village->bn_name }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $application->address }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">সার্ভিস ক্যাটাগরিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row"><label for="category-request">ক্যাটাগরি</label></th>
                                <td>
                                    @if(!$application->category->is_confirmed)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input id="category-request" name="category-request" type="text"
                                                       class="form-control"
                                                       value="{{ $application->category->name }}">
                                            </div>
                                            <div class="col-md-6">
                                                <select id="category" name="category">
                                                    <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>

                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        {{ $application->category->name }}
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">সার্ভিস সাব-ক্যাটাগরিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>নাম</th>
                                @foreach($workMethods as $workMethod)
                                    <th>{{ $workMethod->name }}</th>
                                @endforeach
                                <th>পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody class="text-center">
                            @forelse($application->subCategories as $index => $subCategory)
                                <tr>
                                    <td> {{ en2bnNumber($index+1) }} </td>
                                    <td>
                                        @if(!$subCategory->is_confirmed)
                                            <input name="sub-categories[{{ $index }}][name]" type="text"
                                                   class="form-control" value="{{ $subCategory->name }}">
                                            <input type="hidden" name="sub-categories[{{ $index }}][id]"
                                                   value="{{ $subCategory->id }}">
                                        @else
                                            {{ $subCategory->name }}
                                            <input type="hidden" value="{{ $subCategory->id }}"
                                                   name="confirmed-sub-categories[]">
                                        @endif
                                    </td>
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
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-sub-category">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td><span class="text-muted small">কোন সাব-ক্যাটাগরি নেই</span></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">ডকুমেন্টঃ</p>
                        <div class="row">
                            @if($application->cv)
                                <div class="col-md-3">
                                    <span class="text-muted">বায়োডাটা</span>
                                    <a href="{{ asset('storage/' . $application->cv) }}" target="_blank">PDF</a>
                                </div>
                            @endif
                            @if($application->experience_certificate)
                                <div class="col-md-3">
                                    <span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>
                                    <a href="{{ asset('storage/' . $application->experience_certificate) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $application->experience_certificate) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @foreach($application->user->identities as $identity)
                                <div class="col-md-3">
                                    <a href="{{ asset('storage/' . $identity->path) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $identity->path) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endforeach
                            @if( ! $application->experience_certificate
                                && ! $application->cv && !$application->user->identities->first())
                                <p class="text-muted col-12">কোন ডকুমেন্ট আপলোড করা হয়নি!</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <p class="h4 border-bottom">কাজের ছবিঃ</p>
                        <div class="row">
                            <div class="col-12">
                                @forelse($application->workImages->chunk(2) as $chunk)
                                    <div class="card-deck py-2">
                                        @foreach($chunk as $image)
                                            <div class="card shadow-sm">
                                                <a href="javascript:">
                                                    <img class="card-img-top img-fluid"
                                                         src="{{ asset('storage/' . $image->path) }}"
                                                         alt="Card image cap">
                                                </a>
                                                <div class="card-body">
                                                    <p class="card-text">${{ $image->description }}</p>
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
            </form>
            <form id="reject-request"
                  action="{{ route('backend.request.ind-service-request.destroy', $application->id) }}"
                  method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
            </form>
            <div class="col-md-3">
                <div class="position-sticky right-menu">
                    <div class="row">
                        <div class="col-12">
                            @include('components.notification', ['userId' => $application->user->id])
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            @include('components.sms', ['userId' => $application->user->id])
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 btn-group">
                            <button class="btn btn-success col" form="approve-request">গ্রহণ করুন</button>
                            <button class="btn btn-danger col" form="reject-request">মুছে ফেলুন</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
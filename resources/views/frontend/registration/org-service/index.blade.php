@extends('layouts.frontend.master')

@section('title', 'প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/org-service/index.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container-fluid my-5">

        <h3 class="text-center mb-5">প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন</h3>

        <form method="post" id="registration-form" enctype="multipart/form-data"
              class="d-none d-md-block"
              action="{{ route('organization-service-registration.store') }}">
            {{ csrf_field() }}
            <div id="smartwizard" class="mx-5">
                <ul>
                    <li><a href="#step-1">প্রথম ধাপ<br/>
                            <small>সাধারণ তথ্য</small>
                        </a></li>
                    <li><a href="#step-2">দ্বিতীয় ধাপ<br/>
                            <small>ঠিকানা</small>
                        </a></li>
                    <li><a href="#step-3">তৃতীয় ধাপ<br/>
                            <small>সার্ভিস ক্যাটাগরি</small>
                        </a></li>
                    <li><a href="#step-4">চতুর্থ ধাপ<br/>
                            <small>ডকুমেন্ট</small>
                        </a></li>
                    <li><a href="#step-5">পঞ্চম ধাপ<br/>
                            <small>পেমেন্ট</small>
                        </a></li>
                </ul>

                <div>
                    <div class="p-4" id="step-1">
                        <div class="form-group row mx-5">
                            <label for="name" class="col-3 col-form-label">প্রতিষ্ঠানের নাম <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="name" name="name" type="text" value="{{ old('name') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="description" class="col-3 col-form-label">প্রতিষ্ঠানের বর্ণনা</label>
                            <div class="col-9">
                    <textarea rows="6" id="description" name="description"
                              class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="mobile" class="col-3 col-form-label">মোবাইল নাম্বার <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                            <div class="col-9">
                                <input id="referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="email" class="col-3 col-form-label">ইমেইল</label>
                            <div class="col-9">
                                <input id="email" name="email" type="text" value="{{ old('email') }}"
                                       class="form-control">
                                @include('components.invalid', ['name' => 'email'])
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="website" class="col-3 col-form-label">ওয়েবসাইট</label>
                            <div class="col-9">
                                <input id="website" name="website" type="url" value="{{ old('website') }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="facebook" class="col-3 col-form-label">ফেসবুক</label>
                            <div class="col-9">
                                <input id="facebook" name="facebook" type="url" value="{{ old('facebook') }}"
                                       class="form-control">
                            </div>
                        </div>
                        @if(!$user->nid)
                            <div class="form-group row mx-5">
                                <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                                            class="text-danger">*</span></label>
                                <div class="col-9">
                                    <input id="nid" name="nid" type="number" value="{{ old('nid') }}"
                                           class="form-control" required>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-4" id="step-2">
                        <div class="form-group row mx-5">
                            <label class="col-3 col-form-label">এলাকা <span class="text-danger">*</span></label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-md">
                                        <select name="division" id="division"
                                                data-option-loader-url="{{ route('api.districts') }}"
                                                data-option-loader-target="#district"
                                                data-option-loader-param="division">
                                            <option value="">-- বিভাগ --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="district" id="district"
                                                data-placeholder="-- জেলা --"
                                                data-option-loader-url="{{ route('api.thanas') }}"
                                                data-option-loader-target="#thana"
                                                data-option-loader-param="district"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- জেলা --</option>
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="thana" id="thana"
                                                data-placeholder="-- থানা --"
                                                data-option-loader-url="{{ route('api.unions') }}"
                                                data-option-loader-target="#union"
                                                data-option-loader-param="thana"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- থানা --</option>
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="union" id="union"
                                                data-placeholder="-- ইউনিয়ন --"
                                                data-option-loader-url="{{ route('api.villages') }}"
                                                data-option-loader-target="#village"
                                                data-option-loader-param="union"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- ইউনিয়ন --</option>
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="village" id="village"
                                                data-placeholder="-- এলাকা --"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- এলাকা --</option>
                                        </select>
                                    </div>
                                </div>
                                <label for="no-thana" class="mt-3 checkbox">আমার থানা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-thana" class="mt-2 no-something" name="no-thana">
                                    <span></span>
                                    <input type="text" id="thana-request" name="thana-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।">
                                </label>

                                <label for="no-union" class="checkbox">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-union" class="mt-2 no-something" name="no-union">
                                    <span></span>
                                    <input type="text" id="union-request" name="union-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।">
                                </label>

                                <label for="no-village" class="checkbox">আমার এলাকা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-village" class="mt-2 no-something" name="no-village">
                                    <span></span>
                                    <input type="text" id="village-request" name="village-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="address" class="col-3 col-form-label">ঠিকানা <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-3">
                        <div class="form-group row mx-5">
                            <label for="category" class="col-3 col-form-label">ক্যাটাগরি <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="category" name="category"
                                        data-option-loader-url="{{ route('api.sub-categories') }}"
                                        data-option-loader-target="#sub-categories"
                                        data-option-loader-param="category">
                                    <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>

                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <label for="no-category" class="checkbox mt-4">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-category" class="mt-2 no-something"
                                           name="no-category">
                                    <span></span>
                                    <input type="text" id="category-request" name="category-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="sub-categories"
                                        data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                                        data-option-loader-properties="value=id,text=name"
                                        multiple>
                                </select>


                                <ul id="sub-repeater-container" class="list-group">
                                    <li class="repeater-insert-before d-none"></li>
                                </ul>


                                <div class="mt-4 checkbox">
                                    <label for="no-sub-category">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                                    <input type="checkbox" id="no-sub-category" name="no-sub-category"
                                           class="mt-2 no-something">
                                    <span></span>
                                    <ul id="sub-req-repeater-container" class="list-group input-div">
                                        <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                                            <div class="row">
                                                <input type="text" class="form-control col-md-5 sub-category-name"
                                                       name="sub-category-requests[0][name]"
                                                       placeholder="সাব-ক্যাটাগরির নাম">
                                                <input type="number" class="form-control col-md-5 sub-category-rate"
                                                       name="sub-category-requests[0][rate]"
                                                       placeholder="রেট">
                                                <a class="fa fa-trash col-md-2 align-items-center float-right text-danger delete-image remove-btn d-none"
                                                   href="#"></a>
                                            </div>
                                        </li>
                                        <li class="repeater-insert-before d-none"></li>
                                        <li class="list-group-item border-0">
                                            <button type="button" id="add-new-sub"
                                                    class="btn btn-light float-left shadow-sm"><i
                                                        class="fa fa-plus"></i> আরও
                                            </button>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="more-price" class="col-3 col-form-label">অতিরিক্ত কাজের তথ্য </label>
                            <div class="col-9" id="otirikto-kaj">
                                <div class="row border rounded shadow-sm mt-2 position-relative"
                                     data-repeater-clone="true">
                                    <div class="form-group  col-md-12 row mt-3">
                                        <label for="addtional-pricing-name" class="col-3 col-form-label">কাজের
                                            নামঃ </label>
                                        <div class="col-9">
                                            <input id="addtional-pricing-name" type="text"
                                                   name="additional-pricing[0][name]"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group  col-md-12 row mt-2">
                                        <label for="addtional-pricing-info" class="col-3 col-form-label">তথ্যঃ </label>
                                        <div class="col-9">
                                            <textarea id="addtional-pricing-info" name="additional-pricing[0][info]"
                                                      class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <span class="repeater-insert-before d-none"></span>
                                <button type="button" id="add-new-price"
                                        class="btn btn-light float-left shadow-sm mt-2"><i
                                            class="fa fa-plus"></i> আরও
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-4">
                        <div class="form-group row mx-5">
                            <label for="identities" class="col-3 col-form-label">লোগো <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="logo" name="logo" type="file" accept="image/*"
                                       class="file-picker">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="identities" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের
                                ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                                        class="text-danger">*</span></label>
                            <div class="col-9 d-flex">
                                <input id="identities" name="identities[]" type="file" accept="image/*"
                                       class="file-picker">
                                <input id="identities" name="identities[]" type="file" accept="image/*"
                                       class="file-picker">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="images" class="col-3 col-form-label">কাজের ছবি</label>
                            <div class="col-9">
                                <div class="flex">
                                    @for($i=0; $i<4; $i++)
                                        <div class="flex-fill shadow-sm p-2 mb-2 bg-white rounded">
                                            <label for="images-{{ $i }}-text" class="my-2">বর্ণনা</label>
                                            <textarea id="images-{{ $i }}-text" type="text" class="form-control"
                                                      name="images[{{ $i }}][description]"></textarea>
                                            <input id="images" name="images[{{ $i }}][file]" type="file"
                                                   accept="image/*"
                                                   class="file-picker">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-5">
                        <div class="form-group row mx-5">
                            <label for="" class="col-3 col-form-label">প্যাকেজ নির্ধারণ করুন</label>
                            <div class="col-9">
                                <select name="package" id="package">
                                    <option value="">-- প্যাকেজ নির্ধারণ করুন --</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->properties->groupBy('name')['name'][0]->value }}</option>
                                    @endforeach
                                </select>
                                <div class="tab-content mt-2" id="package-descriptions">
                                    @foreach($packages as $package)
                                        <div class="tab-pane fade" id="package-dscr-{{ $package->id }}">
                                            {{ $package->properties->groupBy('name')['description'][0]->value }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="payment-method" class="col-3 col-form-label"> পেমেন্ট এর মাধ্যম নির্ধারণ
                                করুন</label>
                            <div class="col-9">
                                <select name="payment-method" id="payment-method">
                                    <option value="">-- পেমেন্ট এর মাধ্যম নির্ধারণ করুন --</option>
                                    @foreach($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                                    @endforeach
                                </select>
                                <div id="payment-method-accountId">
                                    @foreach($paymentMethods as $paymentMethod)
                                        <span class="text-primary d-none"
                                              id="payment-method-id-{{ $paymentMethod->id }}">{{ $paymentMethod->accountId }} @if($paymentMethod->account_type)
                                                <i class="text-muted">({{ $paymentMethod->account_type }}
                                                    )</i>@endif</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="from" class="col-3 col-form-label">যে নাম্বার থেকে পাঠানো হয়েছে</label>
                            <div class="col-9">
                                <input type="text" name="from" id="from" class="form-control"
                                       placeholder="কমপক্ষে শেষের চারটি ডিজিট দিতে হবে" value="{{ old('from') }}">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="transaction-id" class="col-3 col-form-label"> Transaction ID দিন</label>
                            <div class="col-9">
                                <input type="text" name="transaction-id" id="transaction-id" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5 mt-5 text-center">
                            <div class="text-center col-12">
                                <button type="submit" class="btn btn-primary w-25">সাবমিট</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>

        <form method="post" id="mo-registration-form" enctype="multipart/form-data"
              class="d-block d-md-none bg-white p-2 rounded shadow-sm"
              action="{{ route('organization-service-registration.store') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="mo-name" class="col-form-label font-weight-bold">প্রতিষ্ঠানের নাম <span
                            class="text-danger">*</span></label>
                <input id="mo-name" name="name" type="text" value="{{ old('name') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-description" class="col-form-label font-weight-bold">প্রতিষ্ঠানের বর্ণনা</label>
                <textarea rows="6" id="mo-description" name="description"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label for="mo-mobile" class="col-form-label font-weight-bold">মোবাইল নাম্বার <span
                            class="text-danger">*</span></label>
                <input id="mo-mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mo-referrer" class="col-form-label font-weight-bold">রেফারার</label>
                <input id="mo-referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mo-email" class="col-form-label font-weight-bold">ইমেইল</label>
                <input id="mo-email" name="email" type="text" value="{{ old('email') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-website" class="col-form-label font-weight-bold">ওয়েবসাইট</label>
                <input id="mo-website" name="website" type="url" value="{{ old('website') }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-facebook" class="col-form-label font-weight-bold">ফেসবুক</label>
                <input id="mo-facebook" name="facebook" type="url" value="{{ old('facebook') }}"
                       class="form-control">
            </div>

            @if(!$user->nid)
                <div class="form-group">
                    <label for="mo-nid" class="col-form-label font-weight-bold">জাতীয় পরিচয়পত্রের নম্বর <span
                                class="text-danger">*</span></label>
                    <input id="mo-nid" name="nid" type="number" value="{{ old('nid') }}"
                           class="form-control" required>
                </div>
            @endif

            <div class="form-group">
                <label class="col-form-label font-weight-bold">এলাকা <span class="text-danger">*</span></label>
                <select name="division" id="mo-division"
                        data-option-loader-url="{{ route('api.districts') }}"
                        data-option-loader-target="#mo-district"
                        data-option-loader-param="division">mo-
                    <option value="">-- বিভাগ --</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                    @endforeach
                </select>

                <select name="district" id="mo-district"
                        data-placeholder="-- জেলা --"
                        data-option-loader-url="{{ route('api.thanas') }}"
                        data-option-loader-target="#mo-thana"
                        data-option-loader-param="district"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- জেলা --</option>
                </select>
                <select name="thana" id="mo-thana"
                        data-placeholder="-- থানা --"
                        data-option-loader-url="{{ route('api.unions') }}"
                        data-option-loader-target="#mo-union"
                        data-option-loader-param="thana"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- থানা --</option>
                </select>
                <select name="union" id="mo-union"
                        data-placeholder="-- ইউনিয়ন --"
                        data-option-loader-url="{{ route('api.villages') }}"
                        data-option-loader-target="#mo-village"
                        data-option-loader-param="union"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- ইউনিয়ন --</option>
                </select>
                <select name="village" id="mo-village"
                        data-placeholder="-- এলাকা --"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- এলাকা --</option>
                </select>
                <label for="mo-no-thana" class="mt-3 checkbox">আমার থানা এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-thana" class="mt-2 no-something" name="no-thana">
                    <span></span>
                    <input type="text" id="thana-request" name="thana-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।">
                </label>

                <label for="mo-no-union" class="checkbox">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-union" class="mt-2 no-something" name="no-union">
                    <span></span>
                    <input type="text" id="union-request" name="union-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।">
                </label>

                <label for="mo-no-village" class="checkbox">আমার এলাকা এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-village" class="mt-2 no-something" name="no-village">
                    <span></span>
                    <input type="text" id="village-request" name="village-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।">
                </label>
            </div>

            <div class="form-group">
                <label for="mo-address" class="col-form-label font-weight-bold">ঠিকানা <span
                            class="text-danger">*</span></label>
                <textarea id="mo-address" rows="8" name="address" required="required"
                          class="form-control">{{ old('address') }}</textarea>
            </div>


            <div class="form-group">
                <label for="mo-category" class="col-form-label font-weight-bold">ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <select id="mo-category" name="category"
                        data-option-loader-url="{{ route('api.sub-categories') }}"
                        data-option-loader-target="#mo-sub-categories"
                        data-option-loader-param="category">
                    <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <label for="mo-no-category" class="checkbox mt-4">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-category" class="mt-2 no-something"
                           name="no-category">
                    <span></span>
                    <input type="text" id="category-request" name="category-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।">
                </label>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">সার্ভিস সাব-ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <select id="mo-sub-categories"
                        data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                        data-option-loader-properties="value=id,text=name"
                        multiple>
                </select>

                <ul id="mo-sub-repeater-container" class="list-group">
                    <li class="repeater-insert-before d-none"></li>
                </ul>

                <label class="mt-4 checkbox" for="mo-no-sub-category">
                    আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-sub-category" name="no-sub-category"
                           class="mt-2 no-something">
                    <span></span>
                </label>
                <ul id="mo-sub-req-repeater-container" class="list-group d-none">
                    <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                        <div class="row">
                            <input type="text" class="form-control col-5 sub-category-name"
                                   name="sub-category-requests[0][name]"
                                   placeholder="সাব-ক্যাটাগরির নাম">
                            <input type="number" class="form-control col-5 sub-category-rate ml-1"
                                   name="sub-category-requests[0][rate]"
                                   placeholder="রেট">
                        </div>
                    </li>
                    <li class="repeater-insert-before d-none"></li>
                </ul>
                <button type="button" class="btn btn-light shadow-sm mt-1 d-none" id="mo-add-new-sub"><i
                            class="fa fa-plus"></i> আরও
                </button>
            </div>

            <div class="form-group">
                <label for="more-price" class="col-form-label font-weight-bold">অতিরিক্ত কাজের তথ্য </label>
                <div id="mo-otirikto-kaj">
                    <div class="border rounded shadow-sm mt-2 position-relative"
                         data-repeater-clone="true">
                        <div class="form-group col-md-12 mt-3">
                            <label for="mo-addtional-pricing-name" class="col-form-label">কাজের
                                নামঃ </label>
                            <input id="mo-addtional-pricing-name" type="text"
                                   name="additional-pricing[0][name]"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-12 mt-2">
                            <label for="mo-addtional-pricing-info" class="col-form-label">তথ্যঃ </label>
                            <textarea id="mo-addtional-pricing-info" name="additional-pricing[0][info]"
                                      class="form-control"></textarea>
                        </div>
                    </div>
                    <span class="repeater-insert-before d-none"></span>
                    <button type="button" class="btn btn-light float-left shadow-sm mt-2" id="mo-add-new-price"><i
                                class="fa fa-plus"></i> আরও
                    </button>
                </div>
            </div>


            <div class="form-group">
                <label for="mo-identities" class="col-form-label mt-3 font-weight-bold">জাতীয় পরিচয়পত্রের
                    ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                            class="text-danger">*</span></label>
                <div class="d-flex">
                    <input id="mo-identities" name="identities[]" type="file" accept="image/*"
                           class="file-picker">
                    <input name="identities[]" type="file" accept="image/*"
                           class="file-picker">
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">কাজের ছবি</label>
                <div>
                    <div class="flex">
                        @for($i=0; $i<4; $i++)
                            <div class="flex-fill shadow-sm p-2 mb-2 bg-white rounded border">
                                <label for="images-{{ $i }}-text" class="my-2">ছবির বর্ণনা</label>
                                <textarea id="images-{{ $i }}-text" type="text" class="form-control"
                                          name="images[{{ $i }}][description]"></textarea>
                                <input id="images" name="images[{{ $i }}][file]" type="file"
                                       accept="image/*"
                                       class="form-control-file file-picker">
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-form-label font-weight-bold">প্যাকেজ নির্ধারণ করুন</label>
                <select name="package" id="mo-package">
                    <option value="">-- প্যাকেজ নির্ধারণ করুন --</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->properties->groupBy('name')['name'][0]->value }}</option>
                    @endforeach
                </select>
                <div class="tab-content mt-2" id="mo-package-descriptions">
                    @foreach($packages as $package)
                        <div class="tab-pane fade" id="mo-package-dscr-{{ $package->id }}">
                            {{ $package->properties->groupBy('name')['description'][0]->value }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="mo-payment-method" class="col-form-label font-weight-bold"> পেমেন্ট এর মাধ্যম নির্ধারণ
                    করুন</label>
                <select name="payment-method" id="mo-payment-method">
                    <option value="">-- পেমেন্ট এর মাধ্যম নির্ধারণ করুন --</option>
                    @foreach($paymentMethods as $paymentMethod)
                        <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
                <div id="mo-payment-method-accountId">
                    @foreach($paymentMethods as $paymentMethod)
                        <span class="text-primary d-none"
                              id="mo-payment-method-id-{{ $paymentMethod->id }}">{{ $paymentMethod->accountId }} @if($paymentMethod->account_type)
                                <i class="text-muted">({{ $paymentMethod->account_type }}
                                    )</i>@endif</span>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="mo-from" class="col-form-label font-weight-bold">যে নাম্বার থেকে পাঠানো হয়েছে</label>
                <input type="text" name="from" id="mo-from" class="form-control"
                       placeholder="কমপক্ষে শেষের চারটি ডিজিট দিতে হবে" value="{{ old('from') }}">
            </div>

            <div class="form-group">
                <label for="mo-transaction-id" class="col-form-label font-weight-bold"> Transaction ID দিন</label>
                <input type="text" name="transaction-id" id="mo-transaction-id" class="form-control">
            </div>

            <div class="form-group row mt-5 text-center">
                <div class="text-center col-12">
                    <button type="submit" class="btn btn-primary">সাবমিট</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\StoreOrg::class, '#registration-form, #mo-registration-form') !!}
    <script src="{{ asset('assets/js/frontend/registration/common.bundle.js') }}"></script>
    <script>
        $('#smartwizard').smartWizard({
            theme: 'arrows',
            lang: {
                next: "পরবর্তী ধাপ",
                previous: "আগের ধাপ"
            },
            useURLhash: true,
            autoAdjustHeight: false
        });

        $('select').selectize({
            plugins: ['option-loader']
        });

        $('#category, #sub-categories, #division, #district, #thana, #union, #village, #package, #payment-method, #mo-category, #mo-sub-categories, #mo-division, #mo-district, #mo-thana, #mo-union, #mo-village, #mo-package, #mo-payment-method').selectize({
            plugins: ['option-loader']
        });

    </script>
@endsection


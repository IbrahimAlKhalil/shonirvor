@extends('layouts.frontend.master')

@section('title', 'সেবা প্রদানকারী অনুরোধ সম্পাদনা করুন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/org-service/index.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">

        <h3 class="text-center mb-5">প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন</h3>

        <form method="post" id="registration-form" enctype="multipart/form-data"
              class="d-none d-md-block"
              action="{{ route('organization-service-registration.update', $org->id) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}

            <div id="smartwizard" class="mx-5">
                <ul class="position-relative">
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
                    <li class="delete-request">
                        <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#warning">মুছে
                            ফেলুন
                        </button>
                    </li>
                </ul>

                <div>
                    <div class="p-4" id="step-1">
                        <div class="form-group row mx-5">
                            <label for="name" class="col-3 col-form-label">প্রতিষ্ঠানের নাম <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="name" name="name" type="text" value="{{ oldOrData('name', $org->name) }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="description" class="col-3 col-form-label">প্রতিষ্ঠান সম্পর্কে</label>
                            <div class="col-9">
                                  <textarea rows="6" id="description" name="description"
                                            class="form-control">{{ oldOrData('description', $org->description) }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="mobile" class="col-3 col-form-label">মোবাইল নম্বর <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="mobile" name="mobile" type="number"
                                       value="{{ oldOrData('mobile', $org->mobile) }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="slug" class="col-3 col-form-label">সার্ভিস লিঙ্ক <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <div class="input-group">
                                    <div class="input-group-prepend d-none d-md-block">
                                            <span class="input-group-text">
                                                {{ route('home') }}/
                                            </span>
                                    </div>
                                    <input type="text" id="slug" name="slug" class="form-control"
                                           value="{{ oldOrData('slug', $org->slug->name) }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="referrer" class="col-3 col-form-label">রেফারার (যদি থাকে)</label>
                            <div class="col-9">
                                <input id="referrer" name="referrer" type="number"
                                       value="{{ oldOrData('referrer', $org->referredBy ? $org->referredBy->user->mobile : '') }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="email" class="col-3 col-form-label">ইমেইল</label>
                            <div class="col-9">
                                <input id="email" name="email" type="text"
                                       value="{{ oldOrData('email', $org->email) }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="website" class="col-3 col-form-label">ওয়েবসাইট</label>
                            <div class="col-9">
                                <input id="website" name="website" type="url"
                                       value="{{ oldOrData('website', $org->website) }}"
                                       class="form-control">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="facebook" class="col-3 col-form-label">ফেসবুক</label>
                            <div class="col-9">
                                <input id="facebook" name="facebook" type="url"
                                       value="{{ oldOrData('facebook', $org->facebook) }}"
                                       class="form-control">
                            </div>
                        </div>

                        @if($first)
                            <div class="form-group row mx-5">
                                <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                                            class="text-danger">*</span></label>
                                <div class="col-9">
                                    <input id="nid" name="nid" type="number"
                                           value="{{ oldOrData('nid', $org->user->nid) }}"
                                           class="form-control" required>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-4" id="step-2">
                        <div class="form-group row mx-5">
                            <label class="col-3 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                            <div class="col-9">
                                <div class="row">
                                    <div class="col-md">
                                        <select name="division" id="division"
                                                data-option-loader-url="{{ route('api.districts') }}"
                                                data-option-loader-target="#district"
                                                data-option-loader-param="division"
                                                data-option-loader-nodisable="true">
                                            <option value="">-- বিভাগ --</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}" {{ selectOpt($org->division_id, $division->id) }}>{{ $division->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="district" id="district"
                                                data-placeholder="-- জেলা --"
                                                data-option-loader-url="{{ route('api.thanas') }}"
                                                data-option-loader-target="#thana"
                                                data-option-loader-param="district"
                                                data-option-loader-properties="value=id,text=bn_name"
                                                data-option-loader-nodisable="true">
                                            @foreach($districts as $district)
                                                <option value="{{ $district->id }}" {{ selectOpt($org->district_id, $district->id) }}>{{ $district->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="thana" id="thana"
                                                data-placeholder="-- থানা --"
                                                data-option-loader-url="{{ route('api.unions') }}"
                                                data-option-loader-target="#union"
                                                data-option-loader-param="thana"
                                                data-option-loader-properties="value=id,text=bn_name"
                                                data-option-loader-nodisable="true">
                                            <option value="">-- থানা --</option>
                                            @foreach($thanas as $thana)
                                                <option value="{{ $thana->id }}" {{ selectOpt($org->thana_id, $thana->id) }}>{{ $thana->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="union" id="union"
                                                data-placeholder="-- ইউনিয়ন --"
                                                data-option-loader-url="{{ route('api.villages') }}"
                                                data-option-loader-target="#village"
                                                data-option-loader-param="union"
                                                data-option-loader-properties="value=id,text=bn_name"
                                                data-option-loader-nodisable="true">
                                            <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                            @foreach($unions as $union)
                                                <option value="{{ $union->id }}" {{ selectOpt($org->union_id, $union->id) }}>{{ $union->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="village" id="village"
                                                data-placeholder="-- এলাকা --"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- এলাকা --</option>
                                            @foreach($villages as $village)
                                                <option value="{{ $village->id }}" {{ selectOpt($org->village_id, $village->id) }}>{{ $village->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <label for="no-thana" class="mt-3 checkbox">আমার থানা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-thana" class="mt-2 no-something"
                                           name="no-thana" {{ checkBox($org->thana->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="thana-request" name="thana-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।"
                                           value="{{ $org->thana->bn_name }}">
                                </label>

                                <label for="no-union" class="checkbox">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-union" class="mt-2 no-something"
                                           name="no-union" {{ checkBox($org->union->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="union-request" name="union-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।"
                                           value="{{ $org->union->bn_name }}">
                                </label>

                                <label for="no-village" class="checkbox">আমার এলাকা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-village" class="mt-2 no-something"
                                           name="no-village" {{ checkBox($org->village->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="village-request" name="village-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।"
                                           value="{{ $org->village->bn_name }}">
                                </label>

                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="address" class="col-3 col-form-label">পূর্ণাঙ্গ ঠিকানা <span class="text-danger">*</span></label>
                            <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control">{{ oldOrData('address', $org->address) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-3">
                        <div class="form-group row mx-5">
                            <label for="category" class="col-3 col-form-label">প্রতিষ্ঠানের সেবার ধরন <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="category" name="category"
                                        data-option-loader-url="{{ route('api.sub-categories') }}"
                                        data-option-loader-target="#sub-categories"
                                        data-option-loader-param="category"
                                        data-option-loader-nodisable="true">
                                    <option value="">-- সেবার ধরন নির্বাচন করুন --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ selectOpt($org->category->id, $category->id) }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <label class="checkbox mt-4">আমার সেবার ধরন এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-category" name="no-category"
                                           class="mt-2 no-something" {{ checkBox(!$org->category->is_confirmed) }}>
                                    <span></span>

                                    <input type="text" id="category-request" name="category-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার সেবার ধরন টাইপ করুন ।"
                                           value="{{ $org->category->name }}">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="sub-categories" class="col-3 col-form-label">আপনার প্রতিষ্ঠানের সেবার ধরন <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="sub-categories"
                                        data-placeholder="-- আপনার প্রতিষ্ঠানের সেবার ধরন নির্বাচন করুন --"
                                        data-option-loader-properties="value=id,text=name">
                                    @php($subCategoryIds = $orgSubCategories->pluck('id')->toArray())
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" @if(in_array($subCategory->id, $subCategoryIds)){{  'selected' }}@endif>{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>

                                <ul id="sub-repeater-container" class="list-group">
                                    @php($count = 0)
                                    @foreach($orgSubCategories as $orgSubCategory)
                                        @if($orgSubCategory->is_confirmed)
                                            <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                                                <div class="row">
                                                    <label class="col-md-6"
                                                           for="sub-category-{{ $count }}-{{ $orgSubCategory->id }}">{{ $orgSubCategory->name }}</label>
                                                    <input type="number" class="form-control col-md-6" placeholder="রেট"
                                                           value="{{ $orgSubCategory->pivot->rate }}"
                                                           id="sub-category-{{ $count }}-{{ $orgSubCategory->id }}"
                                                           name="sub-categories[{{ $count }}][rate]">
                                                    <input type="hidden" name="sub-categories[{{ $count }}][id]"
                                                           value="{{ $orgSubCategory->id }}">
                                                </div>
                                            </li>
                                            @php($count++)
                                        @endif
                                    @endforeach
                                    <li class="repeater-insert-before d-none"></li>
                                </ul>

                                <div class="mt-4 checkbox">
                                    <label for="no-sub-category">আমার প্রতিষ্ঠানের সেবার ধরন এখানে তালিকাভুক্ত নেই ।</label>
                                    <input type="checkbox" id="no-sub-category" name="no-sub-category"
                                           class="mt-2 no-something" {{ checkBox($isNoSubCategory) }}>
                                    <span></span>
                                    <ul id="sub-req-repeater-container" class="list-group input-div">

                                        @php($count = 0)
                                        @foreach($orgSubCategories as $orgSubCategory)
                                            @if(!$orgSubCategory->is_confirmed)
                                                <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                                                    <div class="row">
                                                        <input type="text"
                                                               class="form-control col-md-5 sub-category-name"
                                                               name="sub-category-requests[{{ $count }}][name]"
                                                               placeholder="সেবার ধরনের নাম"
                                                               value="{{ $orgSubCategory->name }}">
                                                        <input type="number"
                                                               class="form-control col-md-5 sub-category-rate"
                                                               name="sub-category-requests[{{ $count }}][rate]"
                                                               placeholder="রেট"
                                                               value="{{ $orgSubCategory->pivot->rate }}">
                                                        @if($count != 0)
                                                            <a class="fa fa-trash col-md-2 align-items-center float-right text-danger delete-image remove-btn d-flex"
                                                               href="#"></a>
                                                        @endif
                                                    </div>
                                                </li>
                                                @php($count++)
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="more-price" class="col-3 col-form-label">প্রাতিষ্ঠানিক তথ্য</label>
                            <div class="col-9" id="otirikto-kaj">
                                @foreach($org->additionalPrices as $key => $additionalPrice)
                                    <div class="row border rounded shadow-sm mt-2 position-relative"
                                         data-repeater-clone="true">
                                        <div class="form-group  col-md-12 row mt-3">
                                            <label for="addtional-pricing-name-{{ $key }}" class="col-3 col-form-label">কাজের
                                                নামঃ </label>
                                            <div class="col-9">
                                                <input id="addtional-pricing-name-{{ $key }}" type="text"
                                                       name="additional-pricing[{{ $key }}][name]"
                                                       class="form-control"
                                                       value="{{ oldOrData('additional-pricing.[' .$key. '].[name]', $additionalPrice->name) }}">
                                            </div>
                                        </div>

                                        <div class="form-group  col-md-12 row mt-2">
                                            <label for="addtional-pricing-info-{{ $key }}"
                                                   class="col-3 col-form-label">তথ্যঃ </label>
                                            <div class="col-9">
                                            <textarea id="addtional-pricing-info-{{ $key }}"
                                                      name="additional-pricing[{{ $key }}][info]"
                                                      class="form-control">{{ oldOrData('additional-pricing.[' .$key. '].[info]', $additionalPrice->info) }}</textarea>
                                            </div>
                                        </div>
                                        @if(!$loop->first)
                                            <span class="cross remove-btn"></span>
                                        @endif
                                    </div>
                                @endforeach
                                <span class="repeater-insert-before d-none"></span>
                                <button type="button" class="btn btn-light float-left shadow-sm mt-2"
                                        id="add-new-price"><i
                                            class="fa fa-plus"></i> আরও
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-4">
                        <div class="form-group row mx-5">
                            <label for="logo" class="col-3 col-form-label">লোগো <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="logo" name="logo" type="file" accept="image/*"
                                       data-image="{{ asset('storage/' . $org->logo) }}"
                                       class="file-picker"
                                       data-error="@if($errors->has('logo')) {{ $errors->first('logo') }} @endif">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="trade-license" class="col-3 col-form-label">ট্রেড লাইসেন্স (যদি থাকে)</label>
                            <div class="col-9">
                                <input id="trade-license" name="trade-license" type="file" accept="image/*"
                                       @if($org->trade_license)data-image="{{ asset('storage/' . $org->trade_license) }}"@endif
                                       class="file-picker"
                                       data-error="@if($errors->has('trade-license')) {{ $errors->first('trade-license') }} @endif">
                            </div>
                        </div>

                        @if ($first)
                            @if($errors->has('identities'))
                                @php($err = ((array) $errors->get('identities')))
                            @endif
                            <div class="form-group row mx-5">
                                <label for="identities" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ - এর স্ক্যান কপি <span
                                            class="text-danger">*</span></label>
                                <div class="col-9">
                                    @foreach($user->identities as $index => $identity)
                                        <input id="identities" name="identities[]" type="file" accept="image/*"
                                               data-image="{{ asset('storage/' . $identity->path) }}"
                                               class="file-picker"
                                               data-error="@if($errors->has('identities') && isset($err[$index])) {{ $err[$index] }} @endif">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="p-4" id="step-5">
                        <div class="form-group row mx-5">
                            <label for="" class="col-3 col-form-label">রেজিস্ট্রেশন প্যাকেজ নির্ধারণ করুন প্যাকেজ নির্ধারণ করুন</label>
                            <div class="col-9">
                                <select name="package" id="package">
                                    <option value="">-- প্যাকেজ নির্ধারণ করুন --</option>
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{ selectOpt($selectedPackage, $package->id) }}>{{ $package->properties->groupBy('name')['name'][0]->value }}</option>
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
                                       placeholder="কমপক্ষে শেষের চারটি ডিজিট দিতে হবে"
                                       value="{{ oldOrData('from', $org->payments->first()?$org->payments->first()->from:null) }}">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="transaction-id" class="col-3 col-form-label"> Transaction ID দিন</label>
                            <div class="col-9">
                                <input type="text" name="transaction-id" id="transaction-id" class="form-control"
                                       value="{{ oldOrData('transactionId', $org->payments->first()?$org->payments->first()->transactionId:null) }}">
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
              action="{{ route('organization-service-registration.update', $org->id) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}

            <div class="form-group">
                <label for="mo-name" class="col-form-label font-weight-bold">প্রতিষ্ঠানের নাম <span
                            class="text-danger">*</span></label>
                <input id="mo-name" name="name" type="text" value="{{ oldOrData('name', $org->name) }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-description" class="col-form-label font-weight-bold">প্রতিষ্ঠান সম্পর্কে</label>
                <textarea rows="6" id="mo-description" name="description"
                          class="form-control">{{ oldOrData('description', $org->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="mo-mobile" class="col-form-label font-weight-bold">মোবাইল নম্বর <span
                            class="text-danger">*</span></label>
                <input id="mo-mobile" name="mobile" type="number"
                       value="{{ oldOrData('mobile', $org->mobile) }}"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mo-slug" class="col-form-label font-weight-bold">সার্ভিস লিঙ্ক <span
                            class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend d-none d-md-block">
                                            <span class="input-group-text">
                                                {{ route('home') }}/
                                            </span>
                    </div>
                    <input type="text" id="mo-slug" name="slug" class="form-control"
                           value="{{ oldOrData('slug', $org->slug->name) }}">
                </div>
            </div>

            <div class="form-group">
                <label for="mo-referrer" class="col-form-label font-weight-bold">রেফারার (যদি থাকে)</label>
                <input id="mo-referrer" name="referrer" type="number"
                       value="{{ oldOrData('referrer', $org->referredBy ? $org->referredBy->user->mobile : '') }}"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="mo-email" class="col-form-label font-weight-bold">ইমেইল</label>
                <input id="mo-email" name="email" type="text"
                       value="{{ oldOrData('email', $org->email) }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-website" class="col-form-label font-weight-bold">ওয়েবসাইট</label>
                <input id="mo-website" name="website" type="url"
                       value="{{ oldOrData('website', $org->website) }}"
                       class="form-control">
            </div>

            <div class="form-group">
                <label for="mo-facebook" class="col-form-label font-weight-bold">ফেসবুক</label>
                <input id="mo-facebook" name="facebook" type="url"
                       value="{{ oldOrData('facebook', $org->facebook) }}"
                       class="form-control">
            </div>

            @if($first)
                <div class="form-group">
                    <label for="mo-nid" class="col-form-label font-weight-bold">জাতীয় পরিচয়পত্রের নম্বর <span
                                class="text-danger">*</span></label>
                    <input id="mo-nid" name="nid" type="number"
                           value="{{ oldOrData('nid', $org->user->nid) }}"
                           class="form-control" required>
                </div>
            @endif

            <div class="form-group">
                <label class="col-form-label font-weight-bold">ঠিকানা <span class="text-danger">*</span></label>
                <select name="division" id="mo-division"
                        data-option-loader-url="{{ route('api.districts') }}"
                        data-option-loader-target="#mo-district"
                        data-option-loader-param="division">
                    <option value="">-- বিভাগ --</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ selectOpt($org->division_id, $division->id) }}>{{ $division->bn_name }}</option>
                    @endforeach
                </select>
                <select name="district" id="mo-district"
                        data-placeholder="-- জেলা --"
                        data-option-loader-url="{{ route('api.thanas') }}"
                        data-option-loader-target="#mo-thana"
                        data-option-loader-param="district"
                        data-option-loader-properties="value=id,text=bn_name">
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ selectOpt($org->district_id, $district->id) }}>{{ $district->bn_name }}</option>
                    @endforeach
                </select>
                <select name="thana" id="mo-thana"
                        data-placeholder="-- থানা --"
                        data-option-loader-url="{{ route('api.unions') }}"
                        data-option-loader-target="#mo-union"
                        data-option-loader-param="thana"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- থানা --</option>
                    @foreach($thanas as $thana)
                        <option value="{{ $thana->id }}" {{ selectOpt($org->thana_id, $thana->id) }}>{{ $thana->bn_name }}</option>
                    @endforeach
                </select>
                <select name="union" id="mo-union"
                        data-placeholder="-- ইউনিয়ন --"
                        data-option-loader-url="{{ route('api.villages') }}"
                        data-option-loader-target="#mo-village"
                        data-option-loader-param="union"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                    @foreach($unions as $union)
                        <option value="{{ $union->id }}" {{ selectOpt($org->union_id, $union->id) }}>{{ $union->bn_name }}</option>
                    @endforeach
                </select>
                <select name="village" id="mo-village"
                        data-placeholder="-- এলাকা --"
                        data-option-loader-properties="value=id,text=bn_name">
                    <option value="">-- এলাকা --</option>
                    @foreach($villages as $village)
                        <option value="{{ $village->id }}" {{ selectOpt($org->village_id, $village->id) }}>{{ $village->bn_name }}</option>
                    @endforeach
                </select>

                <label for="no-thana" class="mt-3 checkbox">আমার থানা এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="no-thana" class="mt-2 no-something"
                           name="no-thana" {{ checkBox($org->thana->is_pending) }}>
                    <span></span>
                    <input type="text" id="thana-request" name="thana-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।"
                           value="{{ $org->thana->bn_name }}">
                </label>

                <label for="no-union" class="checkbox">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="no-union" class="mt-2 no-something"
                           name="no-union" {{ checkBox($org->union->is_pending) }}>
                    <span></span>
                    <input type="text" id="union-request" name="union-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।"
                           value="{{ $org->union->bn_name }}">
                </label>

                <label for="no-village" class="checkbox">আমার এলাকা এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="no-village" class="mt-2 no-something"
                           name="no-village" {{ checkBox($org->village->is_pending) }}>
                    <span></span>
                    <input type="text" id="village-request" name="village-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।"
                           value="{{ $org->village->bn_name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="mo-address" class="col-form-label font-weight-bold">পূর্ণাঙ্গ ঠিকানা <span
                            class="text-danger">*</span></label>
                <textarea id="mo-address" rows="8" name="address" required="required"
                          class="form-control">{{ oldOrData('address', $org->address) }}</textarea>
            </div>

            <div class="form-group">
                <label for="mo-category" class="col-form-label font-weight-bold">প্রতিষ্ঠানের সেবার ধরন <span
                            class="text-danger">*</span></label>
                <select id="mo-category" name="category"
                        data-option-loader-url="{{ route('api.sub-categories') }}"
                        data-option-loader-target="#mo-sub-categories"
                        data-option-loader-param="category">
                    <option value="">-- সেবার ধরন নির্বাচন করুন --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ selectOpt($org->category->id, $category->id) }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <label for="mo-no-category" class="checkbox">আমার সেবার ধরন এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-category" name="no-category"
                           class="mt-2 no-something" {{ checkBox(!$org->category->is_confirmed) }}>
                    <span></span>
                    <input type="text" id="mo-category-request" name="category-request"
                           class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার সেবার ধরন টাইপ করুন ।"
                           value="{{ $org->category->name }}">
                </label>
            </div>

            <div class="form-group">
                <label for="mo-sub-categories" class="col-form-label">আপনার প্রতিষ্ঠানের সেবার ধরন <span
                            class="text-danger">*</span></label>
                <select id="mo-sub-categories"
                        data-placeholder="-- আপনার প্রতিষ্ঠানের সেবার ধরন নির্বাচন করুন --"
                        data-option-loader-properties="value=id,text=name">
                    @php($subCategoryIds = $orgSubCategories->pluck('id')->toArray())
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}" @if(in_array($subCategory->id, $subCategoryIds)){{  'selected' }}@endif>{{ $subCategory->name }}</option>
                    @endforeach
                </select>

                <ul id="mo-sub-repeater-container" class="list-group">
                    @php($count = 0)
                    @foreach($orgSubCategories as $orgSubCategory)
                        @if($orgSubCategory->is_confirmed)
                            <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                                <div class="row">
                                    <label class="col-form-label"
                                           for="mo-sub-category-{{ $count }}-{{ $orgSubCategory->id }}">{{ $orgSubCategory->name }}</label>
                                    <input type="number" class="form-control col-md-6" placeholder="রেট"
                                           value="{{ $orgSubCategory->pivot->rate }}"
                                           id="mo-sub-category-{{ $count }}-{{ $orgSubCategory->id }}"
                                           name="sub-categories[{{ $count }}][rate]">
                                    <input type="hidden" name="sub-categories[{{ $count }}][id]"
                                           value="{{ $orgSubCategory->id }}">
                                </div>
                            </li>
                            @php($count++)
                        @endif
                    @endforeach
                    <li class="repeater-insert-before d-none"></li>
                </ul>

                <label class="mt-4 checkbox" for="mo-no-sub-category">আমার প্রতিষ্ঠানের সেবার ধরন এখানে তালিকাভুক্ত নেই ।
                    <input type="checkbox" id="mo-no-sub-category" name="no-sub-category"
                           class="mt-2 no-something" {{ checkBox($isNoSubCategory) }}>
                    <span></span>
                </label>

                <ul id="mo-sub-req-repeater-container" class="list-group">

                    @php($count = 0)
                    @foreach($orgSubCategories as $orgSubCategory)
                        @if(!$orgSubCategory->is_confirmed)
                            <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                                <div class="row">
                                    <input type="text"
                                           class="form-control col-5 sub-category-name"
                                           name="sub-category-requests[{{ $count }}][name]"
                                           placeholder="সেবার ধরনের নাম"
                                           value="{{ $orgSubCategory->name }}">
                                    <input type="number"
                                           class="form-control col-5 sub-category-rate"
                                           name="sub-category-requests[{{ $count }}][rate]"
                                           placeholder="রেট"
                                           value="{{ $orgSubCategory->pivot->rate }}">
                                    @if($count != 0)
                                        <a class="fa fa-trash col-2 align-items-center float-right text-danger delete-image remove-btn d-flex"
                                           href="#"></a>
                                    @endif
                                </div>
                            </li>
                            @php($count++)
                        @endif
                    @endforeach
                    <li class="repeater-insert-before d-none"></li>
                </ul>
            </div>

            <div class="form-group">
                <label class="col-form-label">প্রাতিষ্ঠানিক তথ্য</label>
                <div id="mo-otirikto-kaj">
                    @foreach($org->additionalPrices as $key => $additionalPrice)
                        <div class="border rounded shadow-sm mt-2 position-relative"
                             data-repeater-clone="true">
                            <div class="form-group  col-md-12 mt-3">
                                <label for="mo-addtional-pricing-name-{{ $key }}" class="col-form-label">কাজের
                                    নামঃ </label>
                                <input id="mo-addtional-pricing-name-{{ $key }}" type="text"
                                       name="additional-pricing[{{ $key }}][name]"
                                       class="form-control"
                                       value="{{ oldOrData('additional-pricing.[' .$key. '].[name]', $additionalPrice->name) }}">
                            </div>

                            <div class="form-group  col-md-12 mt-2">
                                <label for="mo-addtional-pricing-info-{{ $key }}"
                                       class="col-form-label">তথ্যঃ </label>
                                <textarea id="mo-addtional-pricing-info-{{ $key }}"
                                          name="additional-pricing[{{ $key }}][info]"
                                          rows="5"
                                          class="form-control">{{ oldOrData('additional-pricing.[' .$key. '].[info]', $additionalPrice->info) }}</textarea>
                            </div>
                            @if(!$loop->first)
                                <span class="cross remove-btn"></span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <span class="repeater-insert-before d-none"></span>
                <button type="button" class="btn btn-light shadow-sm mt-2" id="mo-add-new-price"><i
                            class="fa fa-plus"></i> আরও
                </button>
            </div>

            <div class="form-group">
                <label for="mo-logo" class="font-weight-bold col-form-label d-block">লোগো <span
                            class="text-danger">*</span></label>
                <input id="mo-logo" name="logo" type="file" accept="image/*"
                       class="file-picker" data-image="{{ asset('storage/' . $org->logo) }}"
                       data-error="@if($errors->has('logo')) {{ $errors->first('logo') }} @endif">
            </div>

            <div class="form-group">
                <label for="mo-trade-license" class="font-weight-bold col-form-label d-block">ট্রেড লাইসেন্স (যদি
                    থাকে)</label>
                <input id="mo-trade-license" name="trade-license" type="file" accept="image/*"
                       class="file-picker" @if($org->trade_license)data-image="{{ asset('storage/' . $org->trade_license) }}"@endif
                       data-error="@if($errors->has('trade-license')) {{ $errors->first('trade-license') }} @endif">
            </div>

            @if ($first)
                @if($errors->has('identities'))
                    @php($err = ((array) $errors->get('identities')))
                @endif
                <div class="form-group">
                    <label for="mo-identities" class="col-form-label font-weight-bold">জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ - এর স্ক্যান কপি<span
                                class="text-danger">*</span></label>
                    <div class="d-flex">
                        @foreach($user->identities as $identity)
                            <input id="mo-identities" name="identities[]" type="file" accept="image/*"
                                   data-image="{{ asset('storage/' . $identity->path) }}"
                                   class="file-picker"
                                   data-error="@if($errors->has('identities') && isset($err[$index])) {{ $err[$index] }} @endif">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="col-form-label font-weight-bold">রেজিস্ট্রেশন প্যাকেজ নির্ধারণ করুন</label>
                <select name="package" id="mo-package">
                    <option value="">-- প্যাকেজ নির্ধারণ করুন --</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}" {{ selectOpt($selectedPackage, $package->id) }}>{{ $package->properties->groupBy('name')['name'][0]->value }}</option>
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
                <label for="mo-from" class="font-weight-bold col-form-label">যে নাম্বার থেকে পাঠানো হয়েছে</label>
                <input type="text" name="from" id="mo-from" class="form-control"
                       placeholder="কমপক্ষে শেষের চারটি ডিজিট দিতে হবে"
                       value="{{ oldOrData('from', $org->payments->first()?$org->payments->first()->from:null) }}">
            </div>

            <div class="form-group">
                <label for="mo-transaction-id" class="font-weight-bold col-form-label"> Transaction ID দিন</label>
                <input type="text" name="transaction-id" id="mo-transaction-id" class="form-control"
                       value="{{ oldOrData('transactionId', $org->payments->first()?$org->payments->first()->transactionId:null) }}">
            </div>

            <div class="form-group row mx-5 mt-5 text-center">
                <div class="text-center col-12">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#warning">মুছে ফেলুন
                    </button>
                    <button type="submit" class="btn btn-primary">সাবমিট</button>
                </div>
            </div>

        </form>
    </div>
    <form action="{{ route('organization-service-registration.destroy', $org->id) }}" method="post">
        @csrf
        @method('delete')
        <div class="modal fade" id="warning" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        সতটিই কি আপনি এটি মুছে ফেলতে চান?
                    </div>
                    <div class="modal-footer border-top-0 justify-content-center row">
                        <button type="button" class="btn btn-success" data-dismiss="modal">না</button>
                        <button type="submit" class="btn btn-danger">হ্যাঁ</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('script')
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\UpdateOrg::class, '#registration-form') !!}
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

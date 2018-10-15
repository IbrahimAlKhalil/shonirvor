@extends('layouts.frontend.master')

@section('title', 'সেবা প্রদানকারী অনুরোধ সম্পাদনা করুন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/org-service/edit.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/common.bundle.js') }}"></script>
@endsection

@section('content')

    <div class="container my-5">

        <h3 class="text-center mb-5">প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন</h3>

        @include('components.success')
        @foreach($errors as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
            {{ $errors }}
        @endforeach

        <form method="post" enctype="multipart/form-data"
              action="{{ route('organization-service-registration.update', $org->id) }}" id="edit-form">
            {{ method_field('put') }}
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
                            <label for="description" class="col-3 col-form-label">প্রতিষ্ঠানের বর্ণনা <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                  <textarea rows="6" id="description" name="description"
                                            class="form-control"
                                            required>{{ oldOrData('description', $org->description) }}</textarea>
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
                            <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                            <div class="col-9">
                                <input id="referrer" name="referrer" type="number"
                                       value="{{ oldOrData('referrer', $org->referrer) }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="email" class="col-3 col-form-label">কাজের ইমেইল</label>
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

                        <div class="form-group row mx-5">
                            <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="nid" name="nid" type="number"
                                       value="{{ oldOrData('nid', $org->user->nid) }}"
                                       class="form-control" required>
                            </div>
                        </div>
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
                            <label for="address" class="col-3 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                            <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control">{{ oldOrData('address', $org->address) }}</textarea>
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
                                        data-option-loader-param="category"
                                        data-option-loader-nodisable="true">
                                    <option>-- ক্যাটাগরি নির্বাচন করুন --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ selectOpt($org->category->id, $category->id) }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                <label for="no-category" class="checkbox mt-4">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-category" name="no-category"
                                           class="mt-2 no-something" {{ checkBox(!$org->category->is_confirmed) }}>
                                    <span></span>

                                    <input type="text" id="category-request" name="category-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।"
                                           value="{{ $org->category->name }}">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="sub-categories"
                                        data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                                        data-option-loader-properties="value=id,text=name"
                                        multiple>
                                    @php($subCategoryIds = $orgSubCategories->pluck('id')->toArray())
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" @if(in_array($subCategory->id, $subCategoryIds)){{  'selected' }}@endif>{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>


                                <ul id="repeater-container" class="list-group">
                                    <li class="repeater-clone mt-2 border-0 list-group-item d-none">
                                        <div class="row">
                                            <label class="col-md-6"></label>
                                            <input type="number" class="form-control col-md-6"
                                                   placeholder="রেট">
                                            <input type="hidden">
                                        </div>
                                    </li>
                                    @foreach($orgSubCategories as $index => $orgSubCategory)
                                        @if($orgSubCategory->is_confirmed)
                                            <li class="mt-2 border-0 list-group-item" data-cloned="true">
                                                <div class="row">
                                                    <label class="col-md-6"
                                                           for="sub-category-{{ $index }}-{{ $orgSubCategory->id }}">{{ $orgSubCategory->name }}</label>
                                                    <input type="number" class="form-control col-md-6" placeholder="রেট"
                                                           value="{{ $orgSubCategory->pivot->rate }}"
                                                           id="sub-category-{{ $index }}-{{ $orgSubCategory->id }}"
                                                           name="sub-categories[{{ $index }}][rate]">
                                                    <input type="hidden" name="sub-categories[{{ $index }}][id]"
                                                           value="{{ $orgSubCategory->id }}">
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>


                                <div class="mt-4 checkbox">
                                    <label for="no-sub-category">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                                    <input type="checkbox" id="no-sub-category" name="no-sub-category"
                                           class="mt-2 no-something" {{ checkBox($isNoSubCategory) }}>
                                    <span></span>
                                    <ul id="req-repeater-container" class="list-group input-div">

                                        @foreach($orgSubCategories as $index => $orgSubCategory)
                                            @if(!$orgSubCategory->is_confirmed)
                                                @if($loop->first)
                                                    <li class="repeater-clone mt-2 border-0 list-group-item">
                                                        <div class="row">
                                                            <input type="text"
                                                                   class="form-control col-md-5 sub-category-name"
                                                                   name="sub-category-requests[0][name]"
                                                                   placeholder="সাব-ক্যাটাগরির নাম">
                                                            <input type="number"
                                                                   class="form-control col-md-5 sub-category-rate"
                                                                   name="sub-category-requests[0][rate]"
                                                                   placeholder="রেট">
                                                            <a class="fa fa-trash col-md-2 align-items-center float-right text-danger delete-image remove-btn d-none"
                                                               href="#"></a>
                                                        </div>
                                                    </li>
                                                @endif

                                                <li class="repeater-clone mt-2 border-0 list-group-item"
                                                    data-cloned="true">
                                                    <div class="row">
                                                        <input type="text"
                                                               class="form-control col-md-5 sub-category-name"
                                                               name="sub-category-requests[{{ $index }}][name]"
                                                               placeholder="সাব-ক্যাটাগরির নাম"
                                                               value="{{ $orgSubCategory->name }}">
                                                        <input type="number"
                                                               class="form-control col-md-5 sub-category-rate"
                                                               name="sub-category-requests[{{ $index }}][rate]"
                                                               placeholder="রেট"
                                                               value="{{ $orgSubCategory->pivot->rate }}">
                                                        <a class="fa fa-trash col-md-2 align-items-center float-right text-danger delete-image remove-btn d-flex"
                                                           href="#"></a>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                        <li class="list-group-item border-0">
                                            <button type="button" class="btn btn-light float-left shadow-sm add-new"><i
                                                        class="fa fa-plus"></i> আরও
                                            </button>
                                        </li>
                                    </ul>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-4">
                        <div class="form-group row mx-5">
                            <label for="identities" class="col-3 col-form-label">লোগো <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="logo" name="logo" type="file" accept="image/*"
                                       class="form-control-file">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="identities" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের
                                ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="identities" name="identities[]" type="file" accept="image/*"
                                       class="form-control-file"
                                       multiple>
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
                                                   class="form-control-file">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="trade-license" class="col-3 col-form-label">ট্রেড লাইসেন্স <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="trade-license" name="trade-license" type="file" accept="image/*"
                                       class="form-control-file">
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <div class="offset-4 col-9">
                                <button type="submit" class="btn btn-primary">সাবমিট</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection


@section('script')
    <script>
        $('#smartwizard').smartWizard({
            theme: 'arrows',
            lang: {
                next: "পরবর্তী ধাপ",
                previous: "আগের ধাপ"
            },
            useURLhash: false
        });

        $('select').selectize({
            plugins: ['option-loader']
        });

    </script>
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\UpdateOrg::class, '#edit-form') !!}
@endsection
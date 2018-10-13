@extends('layouts.frontend.master')

@section('title', 'Edit Individual Service Provider Request')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/ind/edit.bundle.js') }}"></script>
@endsection

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        <h3>আপনার তথ্য সম্পাদনা করুন</h3>

        @include('components.success')

        <form method="post" enctype="multipart/form-data"
              action="{{ route('individual-service-registration.update', $ind->id) }}">
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
                        <div class="form-group row">
                            <label for="mobile" class="col-3 col-form-label">মোবাইল নম্বর <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="mobile" name="mobile" type="number"
                                       value="{{ oldOrData('mobile', $ind->mobile) }}"
                                       class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                                @include('components.invalid', ['name' => 'mobile'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                            <div class="col-9">
                                <input id="referrer" name="referrer" type="number"
                                       value="{{ oldOrData('referrer', $ind->referrer) }}"
                                       class="form-control{{ $errors->has('referrer') ? ' is-invalid' : '' }}">
                                @include('components.invalid', ['name' => 'referrer'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-3 col-form-label">ইমেইল</label>
                            <div class="col-9">
                                <input id="email" name="email" type="text"
                                       value="{{ oldOrData('email', $ind->email) }}"
                                       class="form-control @if($errors->has('email')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'email'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="website" class="col-3 col-form-label">ওয়েবসাইট</label>
                            <div class="col-9">
                                <input id="website" name="website" type="url"
                                       value="{{ oldOrData('website', $ind->website) }}"
                                       class="form-control @if($errors->has('website')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'website'])
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="facebook" class="col-3 col-form-label">ফেসবুক</label>
                            <div class="col-9">
                                <input id="facebook" name="facebook" type="url"
                                       value="{{ oldOrData('facebook', $ind->facebook) }}"
                                       class="form-control @if($errors->has('facebook')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'facebook'])
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="age" class="col-3 col-form-label">বয়স <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="age" name="age" type="number"
                                       value="{{ oldOrData('age', $ind->user->age) }}" required="required"
                                       class="form-control @if($errors->has('age')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'age'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="qualification" class="col-3 col-form-label">যোগ্যতা/অভিজ্ঞতা</label>
                            <div class="col-9">
                                <input id="qualification" name="qualification" type="text" class="form-control here"
                                       value="{{ oldOrData('qualification', $ind->user->qualification) }}">
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-2">
                        <div class="form-group row">
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
                                                <option value="{{ $division->id }}" {{ selectOpt($ind->division_id, $division->id) }}>{{ $division->bn_name }}</option>
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
                                                <option value="{{ $district->id }}" {{ selectOpt($ind->district_id, $district->id) }}>{{ $district->bn_name }}</option>
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
                                                <option value="{{ $thana->id }}" {{ selectOpt($ind->thana_id, $thana->id) }}>{{ $thana->bn_name }}</option>
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
                                                <option value="{{ $union->id }}" {{ selectOpt($ind->union_id, $union->id) }}>{{ $union->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md">
                                        <select name="village" id="village"
                                                data-placeholder="-- এলাকা --"
                                                data-option-loader-properties="value=id,text=bn_name">
                                            <option value="">-- এলাকা --</option>
                                            @foreach($villages as $village)
                                                <option value="{{ $village->id }}" {{ selectOpt($ind->village_id, $village->id) }}>{{ $village->bn_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <label for="no-thana" class="mt-3 checkbox">আমার থানা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-thana" class="mt-2 no-something"
                                           name="no-thana" {{ checkBox($ind->thana->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="thana-request" name="thana-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।"
                                           value="{{ $ind->thana->bn_name }}">
                                </label>

                                <br>
                                <label for="no-union" class="checkbox">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-union" class="mt-2 no-something"
                                           name="no-union" {{ checkBox($ind->union->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="union-request" name="union-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।"
                                           value="{{ $ind->union->bn_name }}">
                                </label>

                                <br>
                                <label for="no-village" class="checkbox">আমার এলাকা এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-village" class="mt-2 no-something"
                                           name="no-village" {{ checkBox($ind->village->is_pending) }}>
                                    <span></span>
                                    <input type="text" id="village-request" name="village-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।"
                                           value="{{ $ind->village->bn_name }}">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="col-3 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                            <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ oldOrData('address', $ind->address) }}</textarea>
                                @include('components.invalid', ['name' => 'address'])
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-3">
                        <div class="form-group row">
                            <label for="category" class="col-3 col-form-label">ক্যাটাগরি <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <select id="category" name="category"
                                        data-option-loader-url="{{ route('api.sub-categories') }}"
                                        data-option-loader-target="#sub-categories"
                                        data-option-loader-param="category"
                                        data-option-loader-nodisable="true"
                                        class="@if($errors->has('category')) is-invalid @endif">
                                    <option>-- ক্যাটাগরি নির্বাচন করুন --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ selectOpt($ind->category->id, $category->id) }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @include('components.invalid', ['name' => 'category'])
                                <label for="no-category" class="checkbox">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।
                                    <input type="checkbox" id="no-category" name="no-category"
                                           class="mt-2 no-something" {{ checkBox(!$ind->category->is_confirmed) }}>
                                    <span></span>
                                    <input type="text" id="category-request" name="category-request"
                                           class="form-control mt-3 mb-4"
                                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।"
                                           value="{{ $ind->category->name }}">
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                                        class="text-danger">*</span></label>
                            <div class="col-9" id="sub-categories-parent" data-route="{{ route('api.work-methods') }}">
                                <select id="sub-categories" name="sub-categories[]"
                                        class="@if($errors->has('sub-categories')) is-invalid @endif"
                                        data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                                        data-option-loader-properties="value=id,text=name"
                                        multiple>
                                    @php($selectedSubCategories = $indSubCategories->pluck('id')->toArray())

                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, $selectedSubCategories)?'selected':'' }}>{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                                @include('components.invalid', ['name' => 'sub-categories'])

                                @foreach($indSubCategories as $subCategoryCount => $subCategory)
                                    <div class="card mt-2" data-cloned="true">
                                        <div class="card-header pb-0 pt-2">{{ $subCategory->name }}</div>
                                        <div class="card-body">
                                            @php($methods = $indWorkMethods[$subCategory->id])
                                            @php($methodIds = $methods->pluck('id')->toArray())

                                            @foreach($workMethods as $methodCount => $method)
                                                @if($method->id != 4)
                                                    <div class="row mt-2">
                                                        <div class="col-md-8">
                                                            <label class="checkbox"
                                                                   for="work-method-{{ $method->id }}-{{ $subCategoryCount }}">{{ $method->name }}
                                                                <input type="checkbox"
                                                                       id="work-method-{{ $method->id }}-{{ $subCategoryCount }}"
                                                                       name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]" {{ checkBox(in_array($method->id, $methodIds)) }}>
                                                                <span></span>
                                                            </label>
                                                            <input type="hidden"
                                                                   name="sub-category-rates[{{ $subCategoryCount }}][id]"
                                                                   value="{{ $subCategory->id }}">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control" placeholder="রেট"
                                                                   name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][rate]"
                                                                   value="@if(in_array($method->id, $methodIds)){{ $methods->filter(function($item)use($method){return $item->id == $method->id;})->first()->pivot->rate }}@endif">
                                                            <input type="hidden"
                                                                   name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][id]"
                                                                   value="{{ $method->id }}">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row mt-2">
                                                        <div class="col-md-8">
                                                            <label for="work-method-4-0" class="checkbox">চুক্তি ভিত্তিক
                                                                <input type="checkbox"
                                                                       id="work-method-{{ $method->id }}-{{ $subCategoryCount }}"
                                                                       name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]">
                                                            </label>
                                                            <input type="hidden"
                                                                   name="sub-category-rates[{{ $subCategoryCount }}][id]"
                                                                   value="{{ $subCategory->id }}">
                                                            <input type="hidden"
                                                                   name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][id]"
                                                                   value="{{ $method->id }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                                <div class="card mt-2 repeater-clone d-none">
                                    <div class="card-header pb-0 pt-2"></div>
                                    <div class="card-body"></div>
                                </div>

                                <div class="mt-4 checkbox">
                                    <label for="no-sub-category">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                                    <input type="checkbox" id="no-sub-category" name="no-sub-category"
                                           class="mt-2 no-something" {{ checkBox(!!$pendingSubCategories) }}>
                                    <span></span>
                                    <div class="input-div" id="sub-category-request">
                                        @foreach($pendingSubCategories as $subCategoryCount => $subCategory)
                                            <div class="card mt-2 repeater-clone" data-cloned="true">
                                                <div class="card-header pt-2 m-0 row">
                                                    <div class="col-md-9"><input type="text" class="form-control"
                                                                                 name="sub-category-requests[{{ $subCategoryCount }}][name]"
                                                                                 placeholder="আমার সাব-ক্যাটাগরির নাম"
                                                                                 value="{{ $subCategory->name }}"></div>
                                                    <div class="col-md-3">
                                                        <a class="fa fa-trash float-right text-danger remove-btn"
                                                           href="#"></a>
                                                    </div>
                                                </div>
                                                <div class="card-body">

                                                    @php($methods = $indWorkMethods[$subCategory->id])
                                                    @php($methodIds = $methods->pluck('id')->toArray())

                                                    @foreach($workMethods as $methodCount => $method)
                                                        @if($method->id != 4)
                                                            <div class="row mt-2">
                                                                <div class="col-md-8">
                                                                    <label class="checkbox"
                                                                           for="req-work-method-{{ $method->id }}-{{ $methodCount }}">{{ $method->name }}
                                                                        <input type="checkbox"
                                                                               id="req-work-method-{{ $method->id }}-{{ $methodCount }}"
                                                                               name="sub-category-requests[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]" {{ checkBox(in_array($method->id, $methodIds)) }}>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <div class="col">
                                                                    <input type="text" class="form-control"
                                                                           placeholder="রেট"
                                                                           name="sub-category-requests[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][rate]"
                                                                           value="@if(in_array($method->id, $methodIds)){{ $methods->filter(function($item)use($method){return $item->id == $method->id;})->first()->pivot->rate }}@endif">
                                                                    <input type="hidden"
                                                                           name="sub-category-requests[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][id]"
                                                                           value="{{ $method->id }}">
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="row mt-2">
                                                                <div class="col-md-8">
                                                                    <label class="checkbox"
                                                                           for="req-work-method-{{ $method->id }}-{{ $methodCount }}">চুক্তি
                                                                        ভিত্তিক
                                                                        <input type="checkbox"
                                                                               id="req-work-method-{{ $method->id }}-{{ $methodCount }}"
                                                                               name="sub-category-requests[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]" {{ checkBox(in_array($method->id, $methodIds)) }}>
                                                                        <span></span>
                                                                    </label>
                                                                    <input type="hidden"
                                                                           name="sub-category-requests[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][id]"
                                                                           value="{{ $method->id }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                            </div>
                                        @endforeach


                                        <div class="card mt-2 repeater-clone d-none">
                                            <div class="card-header pt-2 m-0 row">
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control"
                                                           name="sub-category-requests[0][name]"
                                                           placeholder="আমার সাব-ক্যাটাগরির নাম">
                                                </div>
                                                <div class="col-md-3">
                                                    <a class="fa fa-trash float-right text-danger remove-btn d-none"
                                                       href="#"></a>
                                                </div>
                                            </div>
                                            <div class="card-body">

                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new"><i
                                                    class="fa fa-plus"></i> আরও
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4" id="step-4">
                        <div class="form-group row mx-5">
                            <label for="identities" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের
                                ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                                        class="text-danger">*</span></label>
                            <div class="col-9">
                                <input id="identities" name="identities[]" type="file" accept="image/*"
                                       class="form-control-file @if($errors->has('identities')) is-invalid @endif"
                                       multiple>
                                @include('components.invalid', ['name' => 'identities'])
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
                                                   class="form-control-file @if($errors->has('images')) is-invalid @endif">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="cv" class="col-3 col-form-label">বায়োডাটা</label>
                            <div class="col-9">
                                <input id="cv" name="cv" type="file" accept="image/*"
                                       class="form-control-file">
                                @include('components.invalid', ['name' => 'cv'])
                            </div>
                        </div>

                        <div class="form-group row mx-5">
                            <label for="experience-certificate" class="col-3 col-form-label">অভিজ্ঞতা প্রত্যয়ন
                                পত্র</label>
                            <div class="col-9">
                                <input id="experience-certificate" name="experience-certificate" type="file"
                                       accept="image/*"
                                       class="form-control-file">
                                @include('components.invalid', ['name' => 'experience-certificate'])
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
            }
        });

        $('select').selectize({
            plugins: ['option-loader']
        });
    </script>
@endsection
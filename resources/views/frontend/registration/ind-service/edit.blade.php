@extends('layouts.frontend.master')

@section('title', 'Edit Individual Service Provider Request')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/ind/index.bundle.js') }}"></script>
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

            <div class="form-group row">
                <label for="mobile" class="col-3 col-form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
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
                <label for="personal-email" class="col-3 col-form-label">ব্যক্তিগত ইমেইল</label>
                <div class="col-9">
                    <input id="personal-email" name="personal-email" type="text"
                           value="{{ oldOrData('personal-email', $ind->user->email) }}"
                           class="form-control @if($errors->has('personal-email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'personal-email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-3 col-form-label">কাজের ইমেইল</label>
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
                <label class="col-3 col-form-label">এলাকা <span class="text-danger">*</span></label>
                <div class="col-9">
                    <div class="row">
                        <div class="col-md">
                            <select name="division" id="division" class="form-control"
                                    data-option-loader-url="{{ route('api.districts') }}"
                                    data-option-loader-target="#district"
                                    data-option-loader-param="division"
                                    data-option-loader-nodisable="true">
                                <option value="">-- বিভাগ --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ selectOpt($ind->division->id, $division->id) }}>{{ $division->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="district" id="district" class="form-control"
                                    data-placeholder="-- জেলা --"
                                    data-option-loader-url="{{ route('api.thanas') }}"
                                    data-option-loader-target="#thana"
                                    data-option-loader-param="district"
                                    data-option-loader-properties="value=id,text=bn_name"
                                    data-option-loader-nodisable="true">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ selectOpt($ind->district->id, $district->id) }}>{{ $district->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="thana" id="thana" class="form-control"
                                    data-placeholder="-- থানা --"
                                    data-option-loader-url="{{ route('api.unions') }}"
                                    data-option-loader-target="#union"
                                    data-option-loader-param="thana"
                                    data-option-loader-properties="value=id,text=bn_name"
                                    data-option-loader-nodisable="true">
                                <option value="">-- থানা --</option>
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}" {{ selectOpt($ind->thana->id, $thana->id) }}>{{ $thana->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" id="union" class="form-control"
                                    data-placeholder="-- ইউনিয়ন --"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" {{ selectOpt($ind->union->id, $union->id) }}>{{ $union->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="village" id="village" class="form-control"
                                    data-placeholder="-- এলাকা --"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- এলাকা --</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village->id }}" {{ selectOpt($ind->village->id, $village->id) }}>{{ $village->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <label for="no-thana" class="mt-3">আমার থানা এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-thana" class="mt-2 no-something"
                           name="no-thana" {{ checkBox($ind->thana->is_pending) }}>
                    <input type="text" id="thana-request" name="thana-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।" value="{{ $ind->thana->bn_name }}">

                    <br>
                    <label for="no-union">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-union" class="mt-2 no-something"
                           name="no-union" {{ checkBox($ind->union->is_pending) }}>
                    <input type="text" id="union-request" name="union-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।" value="{{ $ind->union->bn_name }}">

                    <br>
                    <label for="no-village">আমার এলাকা এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-village" class="mt-2 no-something"
                           name="no-village" {{ checkBox($ind->village->is_pending) }}>
                    <input type="text" id="village-request" name="village-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।" value="{{ $ind->village->bn_name }}">
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

            <div class="form-group row">
                <label for="category" class="col-3 col-form-label">ক্যাটাগরি <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select id="category" name="category"
                            data-option-loader-url="{{ route('api.sub-categories') }}"
                            data-option-loader-target="#sub-categories"
                            data-option-loader-param="category"
                            data-option-loader-nodisable="true"
                            class="form-control @if($errors->has('category')) is-invalid @endif">
                        <option>-- ক্যাটাগরি নির্বাচন করুন --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ selectOpt($ind->category->id, $category->id) }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    @include('components.invalid', ['name' => 'category'])
                    <label for="no-category">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-category" name="no-category"
                           class="mt-2 no-something" {{ checkBox(!$ind->category->is_confirmed) }}>
                    <input type="text" id="category-request" name="category-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।" value="{{ $ind->category->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <div class="col-9" id="sub-categories-parent" data-route="{{ route('api.work-methods') }}">
                    <select id="sub-categories" name="sub-categories[]"
                            class="form-control @if($errors->has('sub-categories')) is-invalid @endif"
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
                                                <label for="work-method-{{ $method->id }}-{{ $subCategoryCount }}">{{ $method->name }}</label>
                                                <input type="checkbox"
                                                       id="work-method-{{ $method->id }}-{{ $subCategoryCount }}"
                                                       name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]" {{ checkBox(in_array($method->id, $methodIds)) }}>
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
                                                <label for="work-method-4-0">চুক্তি ভিত্তিক</label>
                                                <input type="checkbox"
                                                       id="work-method-{{ $method->id }}-{{ $subCategoryCount }}"
                                                       name="sub-category-rates[{{ $subCategoryCount }}][work-methods][{{ $methodCount }}][checkbox]">
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

                    <label for="no-sub-category" class="mt-4">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-sub-category" name="no-sub-category" class="mt-2 no-something" {{ checkBox(!!$pendingSubCategories) }}>
                    <div class="input-div" id="sub-category-request">
                        @foreach($pendingSubCategories as $subCategoryCount => $subCategory)
                            <div class="card mt-2 repeater-clone" data-cloned="true">
                                <div class="card-header pt-2 m-0 row">
                                    <div class="col-md-9"><input type="text" class="form-control" name="sub-category-requests[1][name]" placeholder="আমার সাব-ক্যাটাগরির নাম"></div>
                                    <div class="col-md-3">
                                        <a class="fa fa-trash float-right text-danger remove-btn" href="#"></a>
                                    </div>
                                </div>
                                <div class="card-body">

                                    @php($methods = $indWorkMethods[$subCategory->id])
                                    @php($methodIds = $methods->pluck('id')->toArray())

                                    @foreach($workMethods as $methodCount => $method)
                                        @if($method->id != 4)
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <label for="req-work-method-1-1">ঘন্টা ভিত্তিক</label>
                                                    <input type="checkbox" id="req-work-method-1-1" name="sub-category-requests[1][work-methods][0][checkbox]">
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="form-control" placeholder="রেট" name="sub-category-requests[1][work-methods][0][rate]">
                                                    <input type="hidden" name="sub-category-requests[1][work-methods][0][id]" value="1">
                                                </div>
                                            </div>
                                        @else
                                            <div class="row mt-2">
                                                <div class="col-md-8">
                                                    <label for="req-work-method-4-0">চুক্তি ভিত্তিক</label>
                                                    <input type="checkbox" id="req-work-method-4-0" name="sub-category-requests[0][work-methods][3][checkbox]">
                                                    <input type="hidden" name="sub-category-requests[0][work-methods][3][id]" value="4">
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        @endforeach




                        <div class="card mt-2 repeater-clone">
                            <div class="card-header pt-2 m-0 row">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="sub-category-requests[0][name]"
                                           placeholder="আমার সাব-ক্যাটাগরির নাম">
                                </div>
                                <div class="col-md-3">
                                    <a class="fa fa-trash float-right text-danger remove-btn d-none" href="#"></a>
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

            <div class="form-group row">
                <label for="age" class="col-3 col-form-label">বয়স <span class="text-danger">*</span></label>
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
            <div class="form-group row">
                <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="nid" name="nid" type="number"
                           value="{{ oldOrData('nid', $ind->user->nid) }}"
                           class="form-control @if($errors->has('nid')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'nid'])
                </div>
            </div>

            @if(!$isPicExists)
                <div class="form-group row">
                    <label for="photo" class="col-3 col-form-label">প্রোফাইল ছবি</label>
                    <div class="col-9">
                        <input id="photo" name="photo" type="file" accept="image/*"
                               class="form-control @if($errors->has('photo')) is-invalid @endif">
                        @include('components.invalid', ['name' => 'photo'])
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label for="identities" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="identities" name="identities[]" type="file" accept="image/*"
                           class="form-control @if($errors->has('identities')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'identities'])
                </div>
            </div>

            <div class="form-group row">
                <label for="images" class="col-3 col-form-label">কাজের ছবি</label>
                <div class="col-9">
                    <div class="row">
                        @for($i=0; $i<4; $i++)
                            <div class="col-md-6 shadow-sm p-2 mb-2 bg-white rounded">
                                <input id="images" name="images[{{ $i }}][file]" type="file" accept="image/*"
                                       class="form-control-file @if($errors->has('images')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'images'])
                                <label for="images-{{ $i }}-text" class="mt-2">বর্ণনা</label>
                                <textarea id="images-{{ $i }}-text" type="text" class="form-control"
                                          name="images[{{ $i }}][description]"></textarea>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="experience-certificate" class="col-3 col-form-label">অভিজ্ঞতা প্রত্যয়ন পত্র</label>
                <div class="col-9">
                    <input id="experience-certificate" name="experience-certificate" type="file" accept="image/*"
                           class="form-control">
                    @include('components.invalid', ['name' => 'experience-certificate'])
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-4 col-9">
                    <button type="submit" class="btn btn-primary">হালনাগাদ</button>
                </div>
            </div>
        </form>
    </div>
    <style>
        .no-something + input, .no-something + .input-div {
            display: none;
        }

        .no-something:checked + input, .no-something:checked + .input-div {
            display: block;
        }
    </style>
@endsection
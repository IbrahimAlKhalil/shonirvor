@extends('layouts.backend.master')

@section('title', 'Edit Your Organization Service Provider Profile')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">

        <h3>আপনার তথ্য সম্পাদনা করুন</h3>

        <form method="post" enctype="multipart/form-data"
              action="{{ route('profile.backend.organization-service.update', $provider->id) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">প্রতিষ্ঠানের নাম <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="name" name="name" type="text" value="{{ oldOrData('name', $provider->name) }}"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-4 col-form-label">বর্ণনা <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <textarea rows="6" id="description" name="description"
                              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                              required>{{ oldOrData('description', $provider->description) }}</textarea>
                    @include('components.invalid', ['name' => 'description'])
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile" class="col-4 col-form-label">মোবাইল নম্বর <span class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="mobile" name="mobile" type="number"
                           value="{{ oldOrData('mobile', $provider->mobile) }}"
                           class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'mobile'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">ইমেইল</label>
                <div class="col-8">
                    <input id="email" name="email" type="text"
                           value="{{ oldOrData('email', $provider->email) }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="website" class="col-4 col-form-label">ওয়েবসাইট</label>
                <div class="col-8">
                    <input id="website" name="website" type="url"
                           value="{{ oldOrData('website', $provider->website) }}"
                           class="form-control @if($errors->has('website')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'website'])
                </div>
            </div>

            <div class="form-group row">
                <label for="facebook" class="col-4 col-form-label">ফেসবুক</label>
                <div class="col-8">
                    <input id="facebook" name="facebook" type="url"
                           value="{{ oldOrData('facebook', $provider->facebook) }}"
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
                                    <option value="{{ $division->id }}" {{ selectOpt($provider->division->id, $division->id) }}>{{ $division->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select id="district" name="district" class="form-control"
                                    data-placeholder="-- জেলা --"
                                    data-option-loader-url="{{ route('api.thanas') }}"
                                    data-option-loader-target="#thana"
                                    data-option-loader-param="district"
                                    data-option-loader-properties="value=id,text=bn_name"
                                    data-option-loader-nodisable="true">
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ selectOpt($provider->district->id, $district->id) }}>{{ $district->bn_name }}</option>
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
                                    <option value="{{ $thana->id }}" {{ selectOpt($provider->thana->id, $thana->id) }}>{{ $thana->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" id="union" class="form-control"
                                    data-placeholder="-- ইউনিয়ন --"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" {{ selectOpt($provider->union->id, $union->id) }}>{{ $union->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <label for="no-thana" class="mt-3">আমার থানা এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-thana" class="mt-2 no-something"
                           name="no-thana" {{ checkBox($provider->thana->is_pending) }}>
                    <input type="text" id="thana-request" name="thana-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।" value="{{ $provider->thana->bn_name }}">
                    <br>
                    <label for="no-union">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-union" class="mt-2 no-something"
                           name="no-union" {{ checkBox($provider->union->is_pending) }}>
                    <input type="text" id="union-request" name="union-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।" value="{{ $provider->union->bn_name }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                <div class="col-8">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ oldOrData('address', $provider->address) }}</textarea>
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
                            class="form-control @if($errors->has('category')) is-invalid @endif">
                        <option value="">-- ক্যাটাগরি নির্বাচন করুন --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ selectOpt($provider->category->id, $category->id) }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                    @include('components.invalid', ['name' => 'category'])
                    <label for="no-category">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-category" name="no-category"
                           class="mt-2 no-something" {{ checkBox(!$provider->category->is_confirmed) }}>
                    <input type="text" id="category-request" name="category-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।" value="{{ $provider->category->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <div class="col-9">

                    <select id="sub-categories" name="sub-categories[]"
                            data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                            data-option-loader-properties="value=id,text=name"
                            class="form-control @if($errors->has('sub-categories[]')) is-invalid @endif" multiple>
                        <option value="">-- সাব ক্যাটাগরি নির্বাচন করুন --</option>
                        @php($selectedSubCategories = $provider->subCategories->pluck('id')->toArray())

                        @foreach($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, $selectedSubCategories)?'selected':'' }}>{{ $subCategory->name }}</option>
                        @endforeach
                    </select>
                    @include('components.invalid', ['name' => 'sub-categories'])

                    @php($requestedSubCategories = $provider->subCategories('requested')->get())
                    <label for="no-sub-category" class="mt-4">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-sub-category" name="no-sub-category"
                           class="mt-2 no-something" {{ checkBox($requestedSubCategories->count() >= 1) }}>
                    <div class="input-div">

                        @foreach($requestedSubCategories as $subcategory)
                            <input type="text" name="sub-category-requests[]" class="form-control mt-3"
                                   placeholder="Type your sub-category here." value="{{ $subcategory->name }}">
                        @endforeach
                        <input type="text" name="sub-category-requests[]" class="form-control mt-3"
                               placeholder="Type your sub-category here.">
                        <input type="text" name="sub-category-requests[]" class="form-control mt-3"
                               placeholder="Type your sub-category here.">
                        <input type="text" name="sub-category-requests[]" class="form-control mt-3"
                               placeholder="Type your sub-category here.">
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <label for="nid" class="col-4 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="nid" name="nid" type="number"
                           value="{{ oldOrData('nid', $provider->user->nid) }}"
                           class="form-control @if($errors->has('nid')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'nid'])
                </div>
            </div>

            <div class="form-group row">
                <label for="identities" class="col-4 col-form-label">লোগো <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="logo" name="logo" type="file" accept="image/*"
                           class="form-control @if($errors->has('logo')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'logo'])
                </div>
            </div>

            <div class="form-group row">
                <label for="identities" class="col-4 col-form-label">জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                            class="text-danger">*</span></label>
                <div class="col-8">
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
                                       class="form-control-file @if($errors->has('images')) is-invalid @endif" multiple>
                                @include('components.invalid', ['name' => 'images'])
                                <label for="images-{{ $i }}-text" class="mt-2">বর্ণনা</label>
                                <textarea id="images-{{ $i }}-text" type="text" class="form-control" name="images[{{ $i }}][description]"></textarea>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="trade-license" class="col-4 col-form-label">ট্রেড লাইসেন্স <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="trade-license" name="trade-license" type="file" accept="image/*"
                           class="form-control">
                    @include('components.invalid', ['name' => 'trade-license'])
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-4 col-8">
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
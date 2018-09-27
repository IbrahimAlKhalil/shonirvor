@extends('layouts.frontend.master')

@section('title', 'প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div style="margin-top: 40px;"></div>

    @foreach($errors as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach

    <div class="container">
        <h3>প্রাতিষ্ঠানিক সেবা প্রদানকারী নিবন্ধন</h3>

        @include('components.success')

        <form method="post" enctype="multipart/form-data"
              action="{{ route('organization-service-registration.store') }}">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-3 col-form-label">প্রতিষ্ঠানের নাম <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}">
                    @include('components.invalid', ['name' => 'name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-3 col-form-label">বর্ণনা <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <textarea rows="6" id="description" name="description"
                              class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                              required>{{ old('description') }}</textarea>
                    @include('components.invalid', ['name' => 'description'])
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile" class="col-3 col-form-label">মোবাইল নাম্বার <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                           class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'mobile'])
                </div>
            </div>

            <div class="form-group row">
                <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                <div class="col-9">
                    <input id="referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                           class="form-control{{ $errors->has('referrer') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'referrer'])
                </div>
            </div>

            <div class="form-group row">
                <label for="personal-email" class="col-3 col-form-label">ব্যক্তিগত ইমেইল</label>
                <div class="col-9">
                    <input id="personal-email" name="personal-email" type="text" value="{{ old('personal-email') }}"
                           class="form-control @if($errors->has('personal-email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'personal-email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-3 col-form-label">কাজের ইমেইল</label>
                <div class="col-9">
                    <input id="email" name="email" type="text" value="{{ old('email') }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="website" class="col-3 col-form-label">ওয়েবসাইট</label>
                <div class="col-9">
                    <input id="website" name="website" type="url" value="{{ old('website') }}"
                           class="form-control @if($errors->has('website')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'website'])
                </div>
            </div>

            <div class="form-group row">
                <label for="facebook" class="col-3 col-form-label">ফেসবুক</label>
                <div class="col-9">
                    <input id="facebook" name="facebook" type="url" value="{{ old('facebook') }}"
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
                                    data-option-loader-param="division">
                                <option value="">-- বিভাগ নির্বাচন করুন --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="district" id="district" class="form-control"
                                    data-placeholder="-- জেলা নির্বাচন করুন --"
                                    data-option-loader-url="{{ route('api.thanas') }}"
                                    data-option-loader-target="#thana"
                                    data-option-loader-param="district"
                                    data-option-loader-properties="value=id,text=bn_name"></select>
                        </div>
                        <div class="col-md">
                            <select name="thana" id="thana" class="form-control"
                                    data-placeholder="-- থানা নির্বাচন করুন --"
                                    data-option-loader-url="{{ route('api.unions') }}"
                                    data-option-loader-target="#union"
                                    data-option-loader-param="thana"
                                    data-option-loader-properties="value=id,text=bn_name">
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" id="union" class="form-control"
                                    data-placeholder="-- ইউনিয়ন নির্বাচন করুন --"
                                    data-option-loader-properties="value=id,text=bn_name">
                            </select>
                        </div>
                    </div>
                    <label for="no-thana" class="mt-3">আমার থানা এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-thana" class="mt-2 no-something" name="no-thana">
                    <input type="text" id="thana-request" name="thana-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার থানার নাম টাইপ করুন ।">
                    <br>
                    <label for="no-union">আমার ইউনিয়ন এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-union" class="mt-2 no-something" name="no-union">
                    <input type="text" id="union-request" name="union-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।">
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-3 col-form-label">ঠিকানা <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ old('address') }}</textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>

            <div class="form-group row">
                <label for="category" class="col-3 col-form-label">ক্যাটাগরি <span class="text-danger">*</span></label>
                <div class="col-9">
                    <select id="category" name="category"
                            class="form-control @if($errors->has('category')) is-invalid @endif"
                            data-option-loader-url="{{ route('api.sub-categories') }}"
                            data-option-loader-target="#sub-categories"
                            data-option-loader-param="category">
                        <option>-- ক্যাটাগরি নির্বাচন করুন --</option>

                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @include('components.invalid', ['name' => 'category'])
                    <label for="no-category">আমার ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-category" class="mt-2 no-something" name="no-category">
                    <input type="text" id="category-request" name="category-request" class="form-control mt-3 mb-4"
                           placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।">
                </div>
            </div>

            <div class="form-group row">
                <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <select id="sub-categories" name="sub-categories[]"
                            class="form-control @if($errors->has('sub-categories[]')) is-invalid @endif"
                            data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                            data-option-loader-properties="value=id,text=name"
                            multiple>
                    </select>
                    @include('components.invalid', ['name' => 'sub-categories'])
                    <label for="no-sub-category" class="mt-4">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                    <input type="checkbox" id="no-sub-category" name="no-sub-category" class="mt-2 no-something">
                    <div class="input-div">
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
                <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="nid" name="nid" type="number" value="{{ old('nid') }}"
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
                <label for="identities" class="col-3 col-form-label">লোগো <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="logo" name="logo" type="file" accept="image/*"
                           class="form-control @if($errors->has('logo')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'logo'])
                </div>
            </div>

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
                <label for="trade-license" class="col-3 col-form-label">ট্রেড লাইসেন্স <span
                            class="text-danger">*</span></label>
                <div class="col-9">
                    <input id="trade-license" name="trade-license" type="file" accept="image/*"
                           class="form-control">
                    @include('components.invalid', ['name' => 'trade-license'])
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-4 col-9">
                    <button type="submit" class="btn btn-primary">জমা দিন</button>
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
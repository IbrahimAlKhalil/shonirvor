@extends('layouts.frontend.master')

@section('title', 'Individual Service Provider Registration')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/ind/index.bundle.js') }}"></script>
@endsection

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        @include('components.success')

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $classesToAdd[0] }}" data-toggle="tab" href="#edit-requests">অনুরোধ সম্পাদনা
                    করুন</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $classesToAdd[1] }}" data-toggle="tab" href="#request-account">আরও একাউন্টের জন্য
                    অনুরোধ করুন</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="mt-4"></div>
            <div class="tab-pane fade show {{ $classesToAdd[0] }}" id="edit-requests">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ক্যাটাগরি</th>
                        <th scope="col">অনুরোধের তারিখ</th>
                        <th scope="col">সর্বশেষ হালনাগাদকৃত</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inds as $ind)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>
                                <a href="{{ route('individual-service-registration.edit', $ind->id) }}">{{ $ind->category->name }}</a>
                                @if(!$ind->category->is_confirmed) <span class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif
                            </td>
                            <td>{{ en2bnNumber($ind->created_at->format('d/m/y H:i')) }}</td>
                            <td>{{ en2bnNumber($ind->updated_at->format('d/m/y H:i')) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade {{ $classesToAdd[1] }} show" id="request-account">
                <form method="post" enctype="multipart/form-data" action="{{ route('individual-service-registration.store') }}">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="mobile" class="col-3 col-form-label">মোবাইল নাম্বার <span
                                    class="text-danger">*</span></label>
                        <div class="col-9">
                            <input id="mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                                   class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                   placeholder="01xxxxxxxxx" required>
                            @include('components.invalid', ['name' => 'mobile'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                        <div class="col-9">
                            <input id="referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                                   class="form-control{{ $errors->has('referrer') ? ' is-invalid' : '' }}"
                                   placeholder="01xxxxxxxxx">
                            @include('components.invalid', ['name' => 'referrer'])
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
                                        <option value="">-- বিভাগ --</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="district" id="district" class="form-control"
                                            data-placeholder="-- জেলা --"
                                            data-option-loader-url="{{ route('api.thanas') }}"
                                            data-option-loader-target="#thana"
                                            data-option-loader-param="district"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- জেলা --</option>
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="thana" id="thana" class="form-control"
                                            data-placeholder="-- থানা --"
                                            data-option-loader-url="{{ route('api.unions') }}"
                                            data-option-loader-target="#union"
                                            data-option-loader-param="thana"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- থানা --</option>
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="union" id="union" class="form-control"
                                            data-placeholder="-- ইউনিয়ন --"
                                            data-option-loader-url="{{ route('api.villages') }}"
                                            data-option-loader-target="#village"
                                            data-option-loader-param="union"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- ইউনিয়ন --</option>
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="village" id="village" class="form-control"
                                            data-placeholder="-- এলাকা --"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- এলাকা --</option>
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

                            <br>
                            <label for="no-village">আমার এলাকা এখানে তালিকাভুক্ত নেই ।</label>
                            <input type="checkbox" id="no-village" class="mt-2 no-something" name="no-village">
                            <input type="text" id="village-request" name="village-request" class="form-control mt-3 mb-4"
                                   placeholder="এখানে আপনার এলাকার নাম টাইপ করুন ।">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-3 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
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
                        <div class="col-9" id="sub-categories-parent" data-route="{{ route('api.work-methods') }}">
                            <select id="sub-categories" name="sub-categories[]"
                                    class="form-control @if($errors->has('sub-categories')) is-invalid @endif"
                                    data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                                    data-option-loader-properties="value=id,text=name"
                                    multiple>
                            </select>
                            @include('components.invalid', ['name' => 'sub-categories'])

                            <div class="card mt-2 repeater-clone d-none">
                                <div class="card-header pb-0 pt-2"></div>
                                <div class="card-body"></div>
                            </div>

                            <label for="no-sub-category" class="mt-4">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                            <input type="checkbox" id="no-sub-category" name="no-sub-category" class="mt-2 no-something">
                            <div class="input-div" id="sub-category-request">
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
                        <label for="age" class="col-3 col-form-label">বয়স <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <input id="age" name="age" type="number" value="{{ old('age') }}" required="required"
                                   class="form-control @if($errors->has('age')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'age'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qualification" class="col-3 col-form-label">যোগ্যতা/অভিজ্ঞতা</label>
                        <div class="col-9">
                            <input id="qualification" name="qualification" type="text" class="form-control here"
                                   value="{{ old('qualification') }}">
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
                        <label for="cv" class="col-3 col-form-label">বায়োডাটা</label>
                        <div class="col-9">
                            <input id="cv" name="cv" type="file" accept="image/*"
                                   class="form-control">
                            @include('components.invalid', ['name' => 'cv'])
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
                            <button type="submit" class="btn btn-primary">সাবমিট</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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
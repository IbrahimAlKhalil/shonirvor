@extends('layouts.frontend.master')

@section('title', 'ব্যক্তি সেবা প্রদানকারী নিবন্ধন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
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
                        <th scope="col">সেবা বিভাগ</th>
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
                        <label for="mobile" class="col-4 col-form-label">মোবাইল নাম্বার <span
                                    class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                                   class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                            @include('components.invalid', ['name' => 'mobile'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="referrer" class="col-4 col-form-label">রেফারার</label>
                        <div class="col-8">
                            <input id="referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                                   class="form-control{{ $errors->has('referrer') ? ' is-invalid' : '' }}" required>
                            @include('components.invalid', ['name' => 'referrer'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="personal-email" class="col-4 col-form-label">ব্যক্তিগত ইমেইল</label>
                        <div class="col-8">
                            <input id="personal-email" name="personal-email" type="text" value="{{ old('personal-email') }}"
                                   class="form-control @if($errors->has('personal-email')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'personal-email'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-4 col-form-label">কাজের ইমেইল</label>
                        <div class="col-8">
                            <input id="email" name="email" type="text" value="{{ old('email') }}"
                                   class="form-control @if($errors->has('email')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'email'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="website" class="col-4 col-form-label">ওয়েবসাইট</label>
                        <div class="col-8">
                            <input id="website" name="website" type="url" value="{{ old('website') }}"
                                   class="form-control @if($errors->has('website')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'website'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="facebook" class="col-4 col-form-label">ফেসবুক</label>
                        <div class="col-8">
                            <input id="facebook" name="facebook" type="url" value="{{ old('facebook') }}"
                                   class="form-control @if($errors->has('facebook')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'facebook'])
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-4 col-form-label">এরিয়া <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <div class="row">
                                <div class="col-md">
                                    <select name="district" class="form-control">
                                        <option value="">-- জেলা নির্বাচন করুন --</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="thana" class="form-control">
                                        <option value="">-- থানা নির্বাচন করুন --</option>
                                        @foreach($thanas as $thana)
                                            <option value="{{ $thana->id }}">{{ $thana->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md">
                                    <select name="union" class="form-control">
                                        <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                        @foreach($unions as $union)
                                            <option value="{{ $union->id }}">{{ $union->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label for="no-thana" class="mt-3">আমার থানা এখানে তালিকাভুক্ত নয় ।</label>
                            <input type="checkbox" id="no-thana" class="mt-2 no-something" name="no-thana">
                            <input type="text" id="thana-request" name="thana-request" class="form-control mt-3 mb-4"
                                   placeholder="এখানে আপনার থানার নাম টাইপ করুন ।">
                            <br>
                            <label for="no-union">আমার ইউনিয়ন এখানে তালিকাভুক্ত নয় ।</label>
                            <input type="checkbox" id="no-union" class="mt-2 no-something" name="no-union">
                            <input type="text" id="union-request" name="union-request" class="form-control mt-3 mb-4"
                                   placeholder="এখানে আপনার ইউনিয়নের নাম টাইপ করুন ।">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="address" class="col-4 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                        <div class="col-8">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ old('address') }}</textarea>
                            @include('components.invalid', ['name' => 'address'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="category" class="col-4 col-form-label">সেবা বিভাগ <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <select id="category" name="category"
                                    class="form-control @if($errors->has('category')) is-invalid @endif">
                                <option>-- শ্রেণী নির্বাচন করুন --</option>

                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'category'])
                            <label for="no-category">আমার শ্রেণীবিভাগ এখানে তালিকাভুক্ত নয় ।</label>
                            <input type="checkbox" id="no-category" class="mt-2 no-something" name="no-category">
                            <input type="text" id="category-request" name="category-request" class="form-control mt-3 mb-4"
                                   placeholder="এখানে আপনার ক্যাটাগরি টাইপ করুন ।">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sub-categories" class="col-4 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                                    class="text-danger">*</span></label>
                        <div class="col-8">
                            <select id="sub-categories" name="sub-categories[]"
                                    class="form-control @if($errors->has('sub-categories[]')) is-invalid @endif" multiple>
                                <option>-- সাব ক্যাটাগরি নির্বাচন করুন --</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                            @include('components.invalid', ['name' => 'sub-categories'])
                            <label for="no-sub-category" class="mt-4">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নয় ।</label>
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
                        <label class="col-4 col-form-label">চুক্তি পদ্ধতি <span class="text-danger">*</span></label>
                        <div class="col-8" style="text-transform: capitalize">
                            @foreach($workMethods as $workMethod)
                                <label for="work-method-{{ $workMethod->id }}">{{ $workMethod->name }}</label>
                                <input type="checkbox" id="work-method-{{ $workMethod->id }}" value="{{ $workMethod->id }}"
                                       name="work-methods[]">
                            @endforeach
                            @include('components.invalid', ['name' => 'work-methods'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="age" class="col-4 col-form-label">বয়স <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="age" name="age" type="number" value="{{ old('age') }}" required="required"
                                   class="form-control @if($errors->has('age')) is-invalid @endif">
                            @include('components.invalid', ['name' => 'age'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qualification" class="col-4 col-form-label">যোগ্যতা/অভিজ্ঞতা</label>
                        <div class="col-8">
                            <input id="qualification" name="qualification" type="text" class="form-control here"
                                   value="{{ old('qualification') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nid" class="col-4 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                                    class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="nid" name="nid" type="number" value="{{ old('nid') }}"
                                   class="form-control @if($errors->has('nid')) is-invalid @endif" required>
                            @include('components.invalid', ['name' => 'nid'])
                        </div>
                    </div>

                    @if(!$isPicExists)
                        <div class="form-group row">
                            <label for="photo" class="col-4 col-form-label">প্রোফাইল ছবি</label>
                            <div class="col-8">
                                <input id="photo" name="photo" type="file" accept="image/*"
                                       class="form-control @if($errors->has('photo')) is-invalid @endif">
                                @include('components.invalid', ['name' => 'photo'])
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="images" class="col-4 col-form-label">কাজের ছবি</label>
                        <div class="col-8">
                            <input id="images" name="images[]" type="file" accept="image/*"
                                   class="form-control @if($errors->has('images')) is-invalid @endif" multiple>
                            @include('components.invalid', ['name' => 'images'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="experience-certificate" class="col-4 col-form-label">অভিজ্ঞতা প্রত্যয়ন পত্র</label>
                        <div class="col-8">
                            <input id="experience-certificate" name="experience-certificate" type="file" accept="image/*"
                                   class="form-control" multiple>
                            @include('components.invalid', ['name' => 'experience-certificate'])
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button type="submit" class="btn btn-primary">জমা দিন</button>
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
@extends('layouts.backend.master')

@section('title', 'Edit Your Individual Service Provider Profile')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        <h3>আপনার তথ্য সম্পাদনা করুন</h3>

        @include('components.success')

        <form method="post" enctype="multipart/form-data"
              action="{{ route('profile.backend.individual-service.update', $provider->id) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}

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
                <label class="col-4 col-form-label">এলাকা <span class="text-danger">*</span></label>
                <div class="col-8">
                    <div class="row">
                        <div class="col-md">
                            <select name="district" class="form-control">
                                <option value="">-- জেলা নির্বাচন করুন --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ selectOpt($provider->district->id, $district->id) }}>{{ $district->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="thana" class="form-control">
                                <option value="">-- থানা নির্বাচন করুন --</option>
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}" {{ selectOpt($provider->thana->id, $thana->id) }}>{{ $thana->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" class="form-control">
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
                <label for="category" class="col-4 col-form-label">ক্যাটাগরি <span class="text-danger">*</span></label>
                <div class="col-8">
                    <select id="category" name="category"
                            disabled
                            class="form-control @if($errors->has('category')) is-invalid @endif">
                        <option>{{ $provider->category->name }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="sub-categories" class="col-4 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                            class="text-danger">*</span></label>
                <div class="col-8">

                    <select id="sub-categories" name="sub-categories[]"
                            class="form-control @if($errors->has('sub-categories[]')) is-invalid @endif" multiple>
                        <option>-- সাব ক্যাটাগরি নির্বাচন করুন --</option>
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
                <label class="col-4 col-form-label">চুক্তি পদ্ধতি <span class="text-danger">*</span></label>
                <div class="col-8">
                    @php($providerWorkMethods = $provider->workMethods->pluck('id')->toArray())
                    @foreach($workMethods as $workMethod)
                        <div class="accordion">
                            <div class="card mt-2">
                                @php($checked = checkBox(in_array($workMethod->id, $providerWorkMethods)))
                                <div class="card-header pb-0 pt-2"><label
                                            for="work-{{ $workMethod->id }}"
                                            data-toggle="collapse"
                                            data-target="#work-method-{{ $workMethod->id }}">{{ $workMethod->name }}
                                    </label>
                                    <input type="checkbox"
                                           class="pull-right"
                                           id="work-{{ $workMethod->id }}"
                                           value="{{ $workMethod->id }}"
                                           name="work-methods[{{ $loop->iteration-1 }}][id]"
                                           data-toggle="collapse"
                                           data-target="#work-method-{{ $workMethod->id }}" {{ $checked }}>
                                </div>
                                <div id="work-method-{{ $workMethod->id }}"
                                     class="collapse @if($checked){{ 'show' }}@endif">
                                    <div class="card-body">
                                        <label for="work-price-{{ $workMethod->id }}">টাকার পরিমাণ</label>
                                        <input type="text"
                                               class="form-control"
                                               id="work-price-{{ $workMethod->id }}"
                                               name="work-methods[{{ $loop->iteration-1 }}][rate]"
                                               value="@if($checked){{ $provider->workMethods->find($workMethod->id)->pivot->rate }}@endif">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @include('components.invalid', ['name' => 'work-methods'])
                </div>
            </div>

            <div class="form-group row">
                <label for="qualification" class="col-4 col-form-label">যোগ্যতা/অভিজ্ঞতা</label>
                <div class="col-8">
                    <input id="qualification" name="qualification" type="text" class="form-control here"
                           value="{{ oldOrData('qualification', $provider->user->qualification) }}">
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
                <label for="identities" class="col-4 col-form-label">জাতীয় পরিচয়পত্রের ফটোকপি/পাসপোর্ট/জন্ম সনদ <span
                            class="text-danger">*</span></label>
                <div class="col-8">
                    <input id="identities" name="identities[]" type="file" accept="image/*"
                           class="form-control @if($errors->has('identities')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'identities'])
                </div>
            </div>

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
                           class="form-control">
                    @include('components.invalid', ['name' => 'experience-certificate'])
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
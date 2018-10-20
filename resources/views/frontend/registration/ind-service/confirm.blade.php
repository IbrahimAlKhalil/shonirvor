@extends('layouts.frontend.master')

@section('title', 'Individual Service Provider Registration')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/ind-service/index.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/registration/common.bundle.js') }}"></script>
@endsection

@section('content')

    @include('components.success')

    <div class="container my-5">

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
                <form id="registration-form" method="post" enctype="multipart/form-data"
                      action="{{ route('individual-service-registration.store') }}">
                    {{ csrf_field() }}

                    <div id="smartwizard" class="mx-5 shadow-lg">
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
                                    <label for="mobile" class="col-3 col-form-label">মোবাইল নাম্বার <span
                                                class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input id="mobile" name="mobile" type="number"
                                               value="{{ oldOrData('mobile', $user->mobile) }}"
                                               class="form-control"
                                               placeholder="01xxxxxxxxx" required>
                                    </div>
                                </div>

                                <div class="form-group row mx-5">
                                    <label for="description" class="col-3 col-form-label">বর্ণনা</label>
                                    <div class="col-9">
                                <textarea id="description" name="description"
                                          class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row mx-5">
                                    <label for="referrer" class="col-3 col-form-label">রেফারার</label>
                                    <div class="col-9">
                                        <input id="referrer" name="referrer" type="number" value="{{ old('referrer') }}"
                                               class="form-control"
                                               placeholder="01xxxxxxxxx">
                                    </div>
                                </div>

                                <div class="form-group row mx-5">
                                    <label for="email" class="col-3 col-form-label">ইমেইল</label>
                                    <div class="col-9">
                                        <input id="email" name="email" type="text"
                                               value="{{ oldOrData('email', $user->email) }}"
                                               class="form-control">
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

                                @if(!$user->age)
                                    <div class="form-group row mx-5">
                                        <label for="age" class="col-3 col-form-label">বয়স <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-9">
                                            <input id="age" name="age" type="number" value="{{ old('age') }}"
                                                   required="required"
                                                   class="form-control">
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group row mx-5">
                                    <label for="qualification" class="col-3 col-form-label">শিক্ষাগত যোগ্যতা</label>
                                    <div class="col-9">
                                        <input id="qualification" name="qualification" type="text" class="form-control here"
                                               value="{{ old('qualification') }}">
                                    </div>
                                </div>
                                <div class="form-group row mx-5">
                                    <label for="nid" class="col-3 col-form-label">জাতীয় পরিচয়পত্রের নম্বর <span
                                                class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <input id="nid" name="nid" type="number" value="{{ old('nid') }}"
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
                                    <label for="address" class="col-3 col-form-label">ঠিকানা <span class="text-danger">*</span></label>
                                    <div class="col-9">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control">{{ old('address') }}</textarea>
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

                                <div class="form-group row">
                                    <label for="sub-categories" class="col-3 col-form-label">সার্ভিস সাব-ক্যাটাগরি <span
                                                class="text-danger">*</span></label>
                                    <div class="col-9" id="sub-categories-parent" data-route="{{ route('api.work-methods') }}">
                                        <select id="sub-categories" name="sub-categories[]"
                                                data-placeholder="-- সাব ক্যাটাগরি নির্বাচন করুন --"
                                                data-option-loader-properties="value=id,text=name"
                                                multiple>
                                        </select>

                                        <div class="card mt-2 repeater-clone d-none">
                                            <div class="card-header pb-0 pt-2"></div>
                                            <div class="card-body"></div>
                                        </div>

                                        <div class="mt-4 checkbox">
                                            <label for="no-sub-category">আমার সাব-ক্যাটাগরি এখানে তালিকাভুক্ত নেই ।</label>
                                            <input type="checkbox" id="no-sub-category" name="no-sub-category"
                                                   class="mt-2 no-something">
                                            <span></span>
                                            <div class="input-div" id="sub-category-request">
                                                <div class="card mt-2 repeater-clone">
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
                                <div class="form-group row mx-5">
                                    <label for="pricing-info" class="col-3 col-form-label">মূল্য সম্পর্কে তথ্য <span
                                                class="text-danger">*</span></label>
                                    <div class="col-9">
                                <textarea id="pricing-info" name="pricing-info"
                                          class="form-control" required>{{ old('pricing-info') }}</textarea>
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
                                               class="form-control-fil"
                                               multiple>
                                    </div>
                                </div>

                                <div class="form-group row mx-5">
                                    <label for="images" class="col-3 col-form-label">কাজের ছবি</label>
                                    <div class="col-9">
                                        <div class="flex">
                                            @for($i=0; $i<4; $i++)
                                                <div class="flex-fill shadow-sm p-2 mb-2 bg-white rounded">
                                                    <label for="images-{{ $i }}-text" class="my-2">ছবির বর্ণনা</label>
                                                    <textarea id="images-{{ $i }}-text" type="text" class="form-control"
                                                              name="images[{{ $i }}][description]"></textarea>
                                                    <input id="images" name="images[{{ $i }}][file]" type="file"
                                                           accept="image/*"
                                                           class="form-control-file mt-3">
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
                                    </div>
                                </div>

                                <div class="form-group row mx-5">
                                    <label for="experience-certificate" class="col-3 col-form-label">অভিজ্ঞতা প্রত্যয়ন
                                        পত্র</label>
                                    <div class="col-9">
                                        <input id="experience-certificate" name="experience-certificate" type="file"
                                               accept="image/*"
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
        </div>
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
    {!! JsValidator::formRequest(\App\Http\Requests\StoreInd::class, '#registration-form') !!}
@endsection
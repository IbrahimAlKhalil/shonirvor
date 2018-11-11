@extends('layouts.frontend.master')

@section('title', 'সার্ভিস সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/my-services/ind-service/edit.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9 bg-white rounded p-4">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('storage/' . $service->user->photo) }}"
                             alt="{{ $service->user->name }}" class="img-thumbnail w-100">
                    </div>

                    <div class="col-md-7 d-flex flex-column flex-wrap justify-content-end">
                        <h1>{{ $service->user->name }}</h1>
                        <p class="h5">{{ $service->category->name }}</p>
                        <p class="h5">{{ $service->village->bn_name.', '.$service->union->bn_name.', '.$service->thana->bn_name.', '.$service->district->bn_name.', '.$service->division->bn_name }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <label class="h4 border-bottom" for="description">বর্ণনাঃ</label>
                        <p class="pt-3 text-justify">
                            <textarea name="" id="description" rows="8"
                                      class="w-100 form-control rounded">{{ $service->description }}</textarea>
                        </p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সাধারণ তথ্যঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row"><label for="mobile">মোবাইল নম্বর</label></th>
                                <td><input type="text" id="mobile" name="mobile" class="form-control"
                                           value="{{ $service->mobile }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="email">ইমেইল</label></th>
                                <td><input type="text" id="email" name="mobile" class="form-control"
                                           value="{{ $service->email }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="website">ওয়েবসাইট</label></th>
                                <td><input type="text" id="website" name="website" class="form-control"
                                           value="{{ $service->website }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="facebook">ফেসবুক</label></th>
                                <td><input type="text" id="facebook" name="facebook" class="form-control"
                                           value="{{ $service->facebook }}"></td>
                            </tr>
                            <tr>
                                <th scope="row">জন্ম তারিখ</th>
                                <td>
                                    <div class="input-group mb-3">
                                        @php($dob = \Carbon\Carbon::make($service->user->dob))
                                        <select name="day" type="text" class="form-control mr-2 rounded-right">
                                            <option value="">-- দিন --</option>
                                            @for($i = 1; $i < 32; $i++)
                                                <option value="{{ $i }}" {{ selectOpt($i, $dob->day) }}>{{ en2bnNumber($i) }}</option>
                                            @endfor
                                        </select>
                                        <select name="month" type="text"
                                                class="form-control mr-2 rounded-right rounded-left">
                                            <option value="">-- মাস --</option>
                                            @php($months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'])
                                            @foreach($months as $index => $month)
                                                <option value="{{ ++$index }}" {{ selectOpt($index, $dob->month) }}>{{ $month }}</option>
                                            @endforeach
                                        </select>
                                        <select name="year" type="text" class="form-control rounded-left">
                                            <option value="">-- বছর --</option>
                                            @php($begining = Date('Y') - 50)
                                            @php($ending = Date('Y') - 18)
                                            @for($i = $ending; $i > $begining; $i--)
                                                <option value="{{ $i }}" {{ selectOpt($i, $dob->year) }}>{{ en2bnNumber($i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="qualification">যোগ্যতা/অভিজ্ঞতা</label></th>
                                <td><input type="text" id="qualification" name="qualification" class="form-control"
                                           value="{{ $service->user->qualification }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="nid">জাতীয় পরিচয়পত্রের নম্বর</label></th>
                                <td><input type="text"
                                           id="nid"
                                           name="nid"
                                           class="form-control"
                                           value="{{ $service->user->nid }}">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">ঠিকানাঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>
                                    <select name="division" id="division"
                                            data-option-loader-url="{{ route('api.districts') }}"
                                            data-option-loader-target="#district"
                                            data-option-loader-param="division">
                                        <option value="">-- বিভাগ --</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ selectOpt($service->division_id, $division->id) }}>{{ $division->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">জেলা</th>
                                <td>
                                    <select name="district" id="district"
                                            data-placeholder="-- জেলা --"
                                            data-option-loader-url="{{ route('api.thanas') }}"
                                            data-option-loader-target="#thana"
                                            data-option-loader-param="district"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" {{ selectOpt($service->district_id, $district->id) }}>{{ $district->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="thana-request">থানা</label></th>
                                <td>
                                    <select name="thana" id="thana"
                                            data-placeholder="-- থানা --"
                                            data-option-loader-url="{{ route('api.unions') }}"
                                            data-option-loader-target="#union"
                                            data-option-loader-param="thana"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- থানা --</option>
                                        @foreach($thanas as $thana)
                                            <option value="{{ $thana->id }}" {{ selectOpt($service->thana_id, $thana->id) }}>{{ $thana->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="union-request">ইউনিয়ন</label></th>
                                <td>
                                    <select name="union" id="union"
                                            data-placeholder="-- ইউনিয়ন --"
                                            data-option-loader-url="{{ route('api.villages') }}"
                                            data-option-loader-target="#village"
                                            data-option-loader-param="union"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- ইউনিয়ন নির্বাচন করুন --</option>
                                        @foreach($unions as $union)
                                            <option value="{{ $union->id }}" {{ selectOpt($service->union_id, $union->id) }}>{{ $union->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="village-request">এলাকা</label></th>
                                <td>
                                    <select name="village" id="village"
                                            data-placeholder="-- এলাকা --"
                                            data-option-loader-properties="value=id,text=bn_name">
                                        <option value="">-- এলাকা --</option>
                                        @foreach($villages as $village)
                                            <option value="{{ $village->id }}" {{ selectOpt($service->village_id, $village->id) }}>{{ $village->bn_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><label for="address">ঠিকানা</label></th>
                                <td><input id="address" class="form-control" type="text"
                                           value="{{ $service->address }}"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সার্ভিস ক্যাটাগরিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row">ক্যাটাগরি</th>
                                <td>{{ $service->category->name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">সার্ভিস সাব-ক্যাটাগরিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100 text-center"
                               id="sub-categories">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>নাম</th>
                                @foreach($workMethods as $workMethod)
                                    <th>{{ $workMethod->name }}</th>
                                @endforeach
                                <th>পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($service->subCategories as $index => $subCategory)
                                <tr data-repeater-clone="true">
                                    <td> {{ $index+1 }} </td>
                                    <td>{{ $subCategory->name }}</td>
                                    @php($methods = $indWorkMethods[$subCategory->id])
                                    @php($methodIds = $methods->pluck('id')->toArray())
                                    @foreach($workMethods as $method)
                                        @if($method->id != 4)
                                            @php($currentMethod = $methods->filter(function($item)use($method){return $item->id == $method->id;}))
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text"
                                                           class="form-control"
                                                           value="@if($currentMethod->first()){{ en2bnNumber($currentMethod->first()->pivot->rate) }}@endif">
                                                </div>
                                        @else
                                            <td>
                                                <div class="d-flex justify-content-center align-content-center">
                                                    <label for="no-thana" class="mt-3 checkbox">
                                                        <input type="checkbox" id="no-thana" class="mt-2"
                                                               name="no-thana" {{ checkBox(in_array(4, $methodIds)) }}>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-sub-category">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted small" colspan="6">কোন সাব-ক্যাটাগরি নেই</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new"><i
                                    class="fa fa-plus"></i> আরও যুক্ত করুন
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">ডকুমেন্টঃ</p>
                        <div class="row">
                            @if($service->experience_certificate)
                                <div class="col-md-3">
                                    <span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>
                                    <a href="{{ asset('storage/' . $service->experience_certificate) }}"
                                       target="_blank">
                                        <img src="{{ asset('storage/' . $service->experience_certificate) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @if($service->cv)
                                <div class="col-md-3">
                                    <span class="text-muted">বায়োডাটা</span>
                                    <a href="{{ asset('storage/' . $service->cv) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $service->cv) }}"
                                             class="img-responsive img-thumbnail">
                                    </a>
                                </div>
                            @endif
                            @if( ! $service->experience_certificate
                                && ! $service->cv)
                                <p class="text-muted col-12">কোন ডকুমেন্ট আপলোড করা হয়নি!</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <p class="h4 border-bottom">কাজের ছবিঃ</p>
                        <div class="row">
                            <div class="col-12">
                                @forelse($service->workImages->chunk(2) as $chunk)
                                    <div class="card-deck py-2">
                                        @foreach($chunk as $image)
                                            <div class="card shadow-sm">
                                                <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                                    <img class="card-img-top img-fluid"
                                                         src="{{ asset('storage/' . $image->path) }}">
                                                </a>
                                                <div class="card-body">
                                                    <p class="card-text">{{ $image->description }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <p class="text-muted col-12">কোন ছবি আপলোড করা হয়নি!</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', ['visitor' => indVisitorCount($service->id)])
                    </div>
                </div>
                @if($service->deleted_at == null)
                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('frontend.applications.individual-top-service.index').'?category='.$service->category_id }}">
                                <button type="button" class="btn btn-info btn-block">টপ সার্ভিসের জন্য আবেদন করুন
                                </button>
                            </a>
                        </div>
                    </div>
                @endif
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn btn-info btn-block">প্রোফাইলটি এডিট করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#division, #district, #thana, #union, #village').selectize({
            plugins: [
                'option-loader'
            ]
        });
    </script>
@endsection
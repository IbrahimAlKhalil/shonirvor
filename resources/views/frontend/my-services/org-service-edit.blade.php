@extends('layouts.frontend.master')

@section('title', 'প্রাতিষ্ঠানিক সার্ভিস এডিট- ' . $service->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/my-services/org-service/edit.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <form class="col-md-9 bg-white rounded p-4" method="post" id="update-form" enctype="multipart/form-data"
                  action="{{ route('frontend.my-service.org.update', $service->id) }}">
                {{ method_field('put') }}
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-3">
                        <input type="file"
                               name="logo"
                               class="file-picker"
                               data-image="{{ asset('storage/' . $service->logo) }}">
                    </div>

                    <div class="col-md-7 d-flex flex-column flex-wrap justify-content-end">
                        <h1>{{ $service->name }}</h1>
                        <p class="h5">{{ $service->category->name }}</p>
                        <p class="h5">{{ $service->village->bn_name.', '.$service->union->bn_name.', '.$service->thana->bn_name.', '.$service->district->bn_name.', '.$service->division->bn_name }}</p>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <label class="h4 border-bottom" for="description">বর্ণনাঃ</label>
                        <p class="pt-3 text-justify">
                            <textarea name="description" id="description" rows="5"
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
                                <th scope="row"><label for="name">প্রতিষ্ঠানের নামঃ</label></th>
                                <td><input type="text" id="name" name="name" class="form-control"
                                           value="{{ $service->name }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="mobile">মোবাইল নম্বর</label></th>
                                <td><input type="text" id="mobile" name="mobile" class="form-control"
                                           value="{{ $service->mobile }}"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="email">ইমেইল</label></th>
                                <td><input type="text" id="email" name="email" class="form-control"
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
                                <th scope="row"><label for="service-link">সার্ভিস লিঙ্কঃ</label></th>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend d-none d-md-block">
                                            <span class="input-group-text">
                                                {{ route('home') }}/
                                            </span>
                                        </div>
                                        <input type="text" id="slug" name="slug" class="form-control"
                                               value="{{ $service->slug->name }}">
                                    </div>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <p class="h4 border-bottom">ঠিকানাঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <tbody>
                            <tr>
                                <th scope="row"><label for="division">বিভাগ</label></th>
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
                                <th scope="row"><label for="district">জেলা</label></th>
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
                                <th scope="row"><label for="thana">থানা</label></th>
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
                                <th scope="row"><label for="union">ইউনিয়ন</label></th>
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
                                <th scope="row"><label for="village">এলাকা</label></th>
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
                                <th scope="row">
                                    <label for="address">ঠিকানা</label>
                                </th>
                                <td>
                                    <input id="address" class="form-control"
                                           type="text"
                                           name="address"
                                           value="{{ $service->address }}">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <p class="h4 border-bottom">সেবার ধরনঃ</p>
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

                <div class="row mt-5">
                    <div class="col-12">
                        <p class="h4 border-bottom">সেবাঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">নাম</th>
                                <th scope="col" class="text-center">মূল্য</th>
                                <th scope="col" class="text-center">পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="sub-categories"
                                   data-route="{{ route('api.sub-categories') }}?category={{ $service->category->id }}">
                            @forelse($service->subCategoryRates as $index => $subCategory)
                                <tr data-repeater-clone="true">
                                    <td> {{ en2bnNumber($index+1) }} </td>
                                    <td class="@if($subCategory->is_confirmed){{ 'text-left' }}@endif">
                                        {{ $subCategory->name }}
                                        <input type="hidden" name="sub-categories[{{ $index }}][id]"
                                               value="{{ $subCategory->id }}">
                                    </td>
                                    <td><input type="number" class="form-control"
                                               value="{{ $subCategory->pivot->rate }}"
                                               name="sub-categories[{{ $index }}][rate]">
                                    </td>
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-sub-category">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"><span class="text-muted small">কোন সেবা নেই</span></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new-category"><i
                                    class="fa fa-plus"></i> আরও যুক্ত করুন
                        </button>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <p class="h4 border-bottom">সেবার জন্য অনুরোধ করুনঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">নাম</th>
                                <th scope="col" class="text-center">মূল্য</th>
                                <th scope="col" class="text-center">পদক্ষেপ</th>
                            </tr>
                            </thead>
                            <tbody class="text-center" id="sub-category-requests">
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new-req"><i
                                    class="fa fa-plus"></i> আরও যুক্ত করুন
                        </button>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12" id="otirikto-kaj">
                        <p class="h4 border-bottom">অতিরিক্ত কাজের তথ্যঃ</p>
                        @forelse($service->additionalPrices as $index => $additionalPrice)
                            <div class="row border rounded shadow-sm mt-2 position-relative otirikto-kaj"
                                 data-repeater-clone="true">
                                <div class="form-group  col-md-12 row mt-3">
                                    <label for="kaj-name-{{ $index }}" class="col-3 col-form-label">কাজের
                                        নামঃ </label>
                                    <div class="col-9">
                                        <input id="kaj-name-{{ $index }}" type="text"
                                               name="kaj[{{ $index }}][name]"
                                               class="form-control" value="{{ $additionalPrice->name }}">
                                        <input type="hidden" name="kaj[{{ $index }}][id]"
                                               value="{{ $additionalPrice->id }}">
                                    </div>
                                </div>
                                <div class="form-group  col-md-12 row mt-2">
                                    <label for="kaj-info-{{ $index }}" class="col-3 col-form-label">তথ্যঃ </label>
                                    <div class="col-9">
                                    <textarea id="kaj-info-{{ $index }}"
                                              name="kaj[{{ $index }}][info]"
                                              cols="50"
                                              rows="4"
                                              class="form-control">{{ $additionalPrice->info }}</textarea>
                                    </div>
                                </div>
                                <i class="fa fa-trash-o delete-kaj text-danger" style="cursor: pointer"></i>
                            </div>
                        @empty
                            <div class="text-center">
                                <span class="text-muted">অতিরিক্ত কাজ নেই</span>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-light float-left shadow-sm mt-2" id="add-new-kaj">
                        <i class="fa fa-plus"></i> আরও যুক্ত করুন
                    </button>
                </div>

                <div class="row mt-5">
                    <div class="col-12" id="cover-photo">
                        <p class="h4 border-bottom">কভার ছবিঃ</p>
                        <input type="file"
                               name="cover-photo"
                               class="file-picker"
                               accept="image/*"
                               @if($service->cover_photo)
                               data-image="{{ asset('storage/' . $service->cover_photo) }}"
                                @endif>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <p class="h4 border-bottom">কাজের ছবিঃ</p>
                        <table class="table table-striped table-bordered table-hover table-sm w-100">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ছবি</th>
                                <th scope="col">বর্ণনা</th>
                            </tr>
                            </thead>
                            <tbody id="work-images">
                            @forelse($service->workImages as $index => $image)
                                <tr data-repeater-clone="true">
                                    <td>
                                        {{ $index }}
                                    </td>
                                    <td>
                                        <input type="file"
                                               name="work-images[{{ $image->id }}][file]"
                                               class="file-picker"
                                               data-image="{{ asset('storage/' . $image->path) }}">
                                    </td>
                                    <td>
                                        <textarea type="text"
                                                  rows="4"
                                                  name="work-images[{{ $image->id }}][description]"
                                                  id="des"
                                                  class="form-control">{{ $image->description }}</textarea>
                                    </td>
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-image">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">কোন ছবি আপলোড করা হয়নি</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new-image"><i
                                    class="fa fa-plus"></i> আরও যুক্ত করুন
                        </button>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12 mt-4 text-center">
                        <button class="btn btn-success w-25" type="button" data-toggle="modal" id="submit-btn"
                                data-target="#confirmModal">সাবমিট
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-bottom-0">
                                        <h5 class="modal-title">আপনি কি শিওর?</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                                        <button type="submit" class="btn btn-success" form="update-form">সাবমিট
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\UpdateOrgMyService::class, '#update-form') !!}
@endsection
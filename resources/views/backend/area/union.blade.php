@extends('layouts.backend.master')

@section('title', 'ইউনিয়ন সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/area/modal.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/area/option-loader.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item h4 mb-0">
                            <a href="{{ route('backend.area.division') }}">বিভাগ</a>
                        </li>
                        <li class="breadcrumb-item h4 mb-0">
                            <a href="{{ route('backend.area.district', $thana->district->division_id) }}">জেলা</a>
                        </li>
                        <li class="breadcrumb-item h4 mb-0">
                            <a href="{{ route('backend.area.thana', $thana->district_id) }}">থানা</a>
                        </li>
                        <li class="breadcrumb-item active h4 mb-0">ইউনিয়ন</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                @include('components.success')
                @include('components.error')
                <table class="table table-striped table-bordered table-hover table-sm text-center mt-3 bg-white">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="70%">{{ $thana->bn_name }} থানার ইউনিয়ন সমূহ</th>
                        <th>পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($unions as $key => $union)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><a href="{{ route('backend.area.village', $union->id) }}">{{ $union->bn_name }}</a></td>
                            <td data-item-id="{{ $union->id }}">
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm edit-btn">
                                    <i class="fa fa-pencil-square-o"></i> এডিট
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm delete-btn">
                                    <i class="fa fa-trash-o"></i> ডিলিট
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">এই থানায় কোন ইউনিয়ন অন্তর্ভুক্ত নেই</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <h5 class="card-header">{{ $thana->bn_name }} থানায় নতুন ইউনিয়ন তৈরি করুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('backend.area.union', $thana->id) }}">
                                    {{ csrf_field() }}
                                    <label for="union" class="label">ইউনিয়নের নাম</label>
                                    <input id="union" name="union" class="form-control" type="text">
                                    @include('components.invalid', ['name' => 'sub-category'])
                                    <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">
                                        সাবমিট
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title h5" id="edit-modal-label" data-suffix="ইউনিয়নটি এডিট করুন"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form data-action="{{ route('backend.area.union.update', 1) }}" method="post" id="edit-form">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="modal-body text-left">
                        <div class="form-group row">
                            <label for="division" class="col-sm-2 col-form-label text-right">বিভাগ:</label>
                            <div class="col-sm-10">
                                <select id="division"
                                        data-option-loader-url="{{ route('api.districts') }}"
                                        data-option-loader-target="#district"
                                        data-option-loader-param="division"
                                        data-option-loader-nodisable="true"
                                        data-default-value="{{ $thana->division->id }}">
                                    @foreach($allDivision as $oneDivision)
                                        <option value="{{ $oneDivision->id }}" @if($oneDivision->id == $thana->division->id){{ 'selected' }}@endif>{{ $oneDivision->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="district" class="col-sm-2 col-form-label text-right">জেলা:</label>
                            <div class="col-sm-10">
                                <select id="district"
                                        data-placeholder="-- জেলা --"
                                        data-option-loader-url="{{ route('api.thanas') }}"
                                        data-option-loader-target="#thana"
                                        data-option-loader-param="district"
                                        data-option-loader-nodisable="true"
                                        data-option-loader-properties="value=id,text=bn_name"
                                        data-default-value="{{ $thana->district_id }}">
                                    @foreach($allDistrict as $oneDistrict)
                                        <option value="{{ $oneDistrict->id }}" @if($oneDistrict->id == $thana->district_id){{ 'selected' }}@endif>{{ $oneDistrict->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="thana" class="col-sm-2 col-form-label text-right">থানা:</label>
                            <div class="col-sm-10">
                                <select name="thana_id" id="thana"
                                        data-placeholder="-- থানা --"
                                        data-option-loader-properties="value=id,text=bn_name"
                                        data-default-value="{{ $thana->id }}">
                                    @foreach($allThana as $oneThana)
                                        <option value="{{ $oneThana->id }}" @if($oneThana->id == $thana->id){{ 'selected' }}@endif>{{ $oneThana->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="bn-name" class="col-sm-2 col-form-label text-right">নাম:</label>
                            <div class="col-sm-10">
                                <input type="text" name="bn_name" class="form-control" id="bn-name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল করুন</button>
                        <button type="submit" class="btn btn-success">সাবমিট করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <p class="modal-title h5" id="delete-modal-label" data-prefix="সত্যিই কি আপনি"
                       data-suffix="ইউনিয়নটি মুছে ফেলতে চান?"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <form data-action="{{ route('backend.area.union.destroy', 1) }}" method="post" id="delete-form">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                        <button type="submit" class="btn btn-danger">হ্যাঁ, মুছতে চাই</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
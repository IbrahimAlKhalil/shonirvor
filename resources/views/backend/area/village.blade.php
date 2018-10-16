@extends('layouts.backend.master')

@section('title', 'এলাকা সমূহ')

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
                            <a href="{{ route('backend.area.district', $union->division->id) }}">জেলা</a>
                        </li>
                        <li class="breadcrumb-item h4 mb-0">
                            <a href="{{ route('backend.area.thana', $union->district->id) }}">থানা</a>
                        </li>
                        <li class="breadcrumb-item h4 mb-0">
                            <a href="{{ route('backend.area.union', $union->thana_id) }}">ইউনিয়ন</a>
                        </li>
                        <li class="breadcrumb-item active h4 mb-0">এলাকা</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                @include('components.success')
                @include('components.error')
                <table class="table table-striped table-bordered table-hover table-sm text-center mt-3">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th width="70%">{{ $union->bn_name }} ইউনিয়নের এলাকা সমূহ</th>
                        <th>পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($villages as $key => $village)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $village->bn_name }}</td>
                            <td data-item-id="{{ $village->id }}">
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
                            <td colspan="3">এই ইউনিয়নে কোন এলাকা অন্তর্ভুক্ত নেই</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <h5 class="card-header">{{ $union->bn_name }} ইউনিয়নে নতুন এলাকা তৈরি করুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('backend.area.village', $union->id) }}">
                                    {{ csrf_field() }}
                                    <label for="village" class="label">এলাকার নাম</label>
                                    <input id="village" name="village" class="form-control" type="text">
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
                <form data-action="{{ route('backend.area.village.update', 1) }}" method="post" id="edit-form">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="modal-body text-left">
                        <div class="form-group row">
                            <label for="division" class="col-sm-2 col-form-label text-right">বিভাগ:</label>
                            <div class="col-sm-10">
                                <select id="division" class="form-control"
                                        data-option-loader-url="{{ route('api.districts') }}"
                                        data-option-loader-target="#district"
                                        data-option-loader-param="division"
                                        data-option-loader-nodisable="true">
                                    @foreach($allDivision as $oneDivision)
                                        <option value="{{ $oneDivision->id }}" @if($oneDivision->id == $union->division->id){{ 'selected' }}@endif>{{ $oneDivision->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="district" class="col-sm-2 col-form-label text-right">জেলা:</label>
                            <div class="col-sm-10">
                                <select id="district" class="form-control"
                                        data-placeholder="-- জেলা --"
                                        data-option-loader-url="{{ route('api.thanas') }}"
                                        data-option-loader-target="#thana"
                                        data-option-loader-param="district"
                                        data-option-loader-nodisable="true"
                                        data-option-loader-properties="value=id,text=bn_name">
                                    @foreach($allDistrict as $oneDistrict)
                                        <option value="{{ $oneDistrict->id }}" @if($oneDistrict->id == $union->district->id){{ 'selected' }}@endif>{{ $oneDistrict->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="thana" class="col-sm-2 col-form-label text-right">থানা:</label>
                            <div class="col-sm-10">
                                <select name="thana_id" id="thana" class="form-control"
                                        data-placeholder="-- থানা --"
                                        data-option-loader-url="{{ route('api.unions') }}"
                                        data-option-loader-target="#union"
                                        data-option-loader-param="thana"
                                        data-option-loader-nodisable="true"
                                        data-option-loader-properties="value=id,text=bn_name">
                                    @foreach($allThana as $oneThana)
                                        <option value="{{ $oneThana->id }}" @if($oneThana->id == $union->thana_id){{ 'selected' }}@endif>{{ $oneThana->bn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="union" class="col-sm-2 col-form-label text-right">ইউনিয়ন:</label>
                            <div class="col-sm-10">
                                <select name="union_id" id="union" class="form-control"
                                        data-placeholder="-- ইউনিয়ন --"
                                        data-option-loader-properties="value=id,text=bn_name">
                                    @foreach($allUnion as $oneUnion)
                                        <option value="{{ $oneUnion->id }}" @if($oneUnion->id == $union->id){{ 'selected' }}@endif>{{ $oneUnion->bn_name }}</option>
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
                       data-suffix="এলাকাটি মুছে ফেলতে চান?"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <form data-action="{{ route('backend.area.village.destroy', 1) }}" method="post" id="delete-form">
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
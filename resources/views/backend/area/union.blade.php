@extends('layouts.backend.master')

@section('title', 'বিভাগ সমূহ')

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
                <table class="table table-striped table-bordered table-hover table-sm text-center mt-3">
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
                            <td>{{ $union->bn_name }}</td>
                            <td>
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm" data-toggle="modal" data-target="#editModal{{ $key }}">
                                    <i class="fa fa-pencil-square-o"></i> এডিট
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $key }}">
                                    <i class="fa fa-trash-o"></i> ডিলিট
                                </a>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $key }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p class="modal-title h5">{{ $union->bn_name }} ইউনিয়নটি এডিট করুন</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('backend.area.union.update', $union->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <div class="modal-body text-left">
                                                    <div class="form-group row">
                                                        <label for="division" class="col-sm-2 col-form-label text-right">বিভাগ:</label>
                                                        <div class="col-sm-10">
                                                            <select id="division" class="form-control">
                                                                @foreach($allDivision as $oneDivision)
                                                                    <option @if($oneDivision->id == $thana->division->id){{ 'selected' }}@endif>{{ $oneDivision->bn_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="district" class="col-sm-2 col-form-label text-right">জেলা:</label>
                                                        <div class="col-sm-10">
                                                            <select id="district" class="form-control">
                                                                @foreach($allDistrict as $oneDistrict)
                                                                    <option value="{{ $oneDistrict->id }}" @if($oneDistrict->id == $thana->district_id){{ 'selected' }}@endif>{{ $oneDistrict->bn_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="thana" class="col-sm-2 col-form-label text-right">জেলা:</label>
                                                        <div class="col-sm-10">
                                                            <select name="thana_id" id="thana" class="form-control">
                                                                @foreach($allThana as $oneThana)
                                                                    <option value="{{ $oneThana->id }}" @if($oneThana->id == $thana->id){{ 'selected' }}@endif>{{ $oneThana->bn_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-2 col-form-label text-right">নাম:</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="bn_name" class="form-control" id="name" value="{{ $union->bn_name }}">
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
                                <div class="modal fade" id="deleteModal{{ $key }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom-0">
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি {{ $union->bn_name }} ইউনিয়নটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('backend.area.union.destroy', $union->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                                                    <button type="submit" class="btn btn-danger">হ্যাঁ, মুছতে চাই</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">সাবমিট</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
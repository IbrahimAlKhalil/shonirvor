@extends('layouts.backend.master')

@section('title', 'বিভাগ সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
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
                            <a href="{{ route('backend.area.district', $district->division_id) }}">জেলা</a>
                        </li>
                        <li class="breadcrumb-item active h4 mb-0">থানা</li>
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
                        <th width="70%">{{ $district->bn_name }} জেলার থানা সমূহ</th>
                        <th>পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($thanas as $key => $thana)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td><a href="{{ route('backend.area.union', $thana->id) }}">{{ $thana->bn_name }}</a></td>
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
                                                <p class="modal-title h5">{{ $thana->bn_name }} থানাটি এডিট করুন</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('backend.area.thana.update', $thana->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <div class="modal-body text-left">
                                                    <div class="form-group row">
                                                        <label for="division" class="col-sm-2 col-form-label text-right">বিভাগ:</label>
                                                        <div class="col-sm-10">
                                                            <select id="division" class="form-control">
                                                                @foreach($allDivision as $oneDivision)
                                                                    <option @if($oneDivision->id == $district->division_id){{ 'selected' }}@endif>{{ $oneDivision->bn_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="district" class="col-sm-2 col-form-label text-right">জেলা:</label>
                                                        <div class="col-sm-10">
                                                            <select name="district_id" id="district" class="form-control">
                                                                @foreach($allDistrict as $oneDistrict)
                                                                    <option value="{{ $oneDistrict->id }}" @if($oneDistrict->id == $district->id){{ 'selected' }}@endif>{{ $oneDistrict->bn_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-2 col-form-label text-right">নাম:</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="bn_name" class="form-control" id="name" value="{{ $thana->bn_name }}">
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
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি {{ $thana->bn_name }} থানাটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('backend.area.thana.destroy', $thana->id) }}" method="post">
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
                            <td colspan="3">এই জেলায় কোন থানা অন্তর্ভুক্ত নেই</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <h5 class="card-header">{{ $district->bn_name }} জেলায় নতুন থানা তৈরি করুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('backend.area.thana', $district->id) }}">
                                    {{ csrf_field() }}
                                    <label for="thana" class="label">থানার নাম</label>
                                    <input id="thana" name="thana" class="form-control" type="text">
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
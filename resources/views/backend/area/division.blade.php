@extends('layouts.backend.master')

@section('title', 'বিভাগ সমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/area/modal.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h4 mb-0">বিভাগ</li>
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
                            <th width="10%">#</th>
                            <th width="60%">বিভাগ সমূহ</th>
                            <th>পদক্ষেপ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($divisions as $key => $division)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>
                                <a href="{{ route('backend.area.district', $division->id) }}">{{ $division->bn_name }}</a>
                            </td>
                            <td data-item-id="{{ $division->id }}">
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
                            <td colspan="3">কোন বিভাগ অন্তর্ভুক্ত নেই</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <h5 class="card-header">নতুন বিভাগ তৈরি করুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('backend.area.division.store') }}">
                                    {{ csrf_field() }}
                                    <label for="division" class="label">বিভাগের নাম</label>
                                    <input id="division" name="division" class="form-control" type="text">
                                    <button class="mt-3 btn btn-secondary btn-success rounded float-right" type="submit">সাবমিট</button>
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
                    <p class="modal-title h5" id="edit-modal-label" data-suffix="বিভাগটি এডিট করুন"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form data-action="{{ route('backend.area.division.update', 1) }}" method="post" id="edit-form">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <div class="modal-body text-left">
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
    <div class="modal fade" id="delete-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <p class="modal-title h5" id="delete-modal-label" data-prefix="সত্যিই কি আপনি" data-suffix="বিভাগটি মুছে ফেলতে চান?"></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <form data-action="{{ route('backend.area.division.destroy', 1) }}" method="post" id="delete-form">
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
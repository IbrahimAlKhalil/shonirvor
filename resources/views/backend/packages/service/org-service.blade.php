@extends('layouts.backend.master')

@section('title', 'সার্ভিস প্যাকেজসমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <h3>সার্ভিস প্যাকেজসমূহ</h3>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                    <div class="col-12 mt-3">
                        <button class="w-100 btn  btn-secondary" data-toggle="modal" data-target="#create-package-modal">নতুন প্যাকেজ তৈরি করুন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-package-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">নতুন প্যাকেজ তৈরি করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Hello</p>
                </div>
            </div>
        </div>
    </div>
@endsection
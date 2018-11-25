@extends('layouts.frontend.master')

@section('title', 'বিজ্ঞাপন এডিট এর আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/ad/edit.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container d-flex justify-content-center">
        <div class="bg-white mt-lg-4 p-4 rounded row">
            <div class="col-md-12 mb-3 p-0">
                <input type="file" id="image" name="image"
                       form="update-form"
                       class="file-picker{{ $errors->has('image') ? ' is-invalid' : '' }}"
                       data-image="{{ asset('storage/' . $image) }}">
                @include('components.invalid', ['name' => 'image'])
            </div>

            <div class="col-md-12 mb-3 p-0">
                <input type="url"
                       form="update-form"
                       class="form-control" name="url" placeholder="লিঙ্ক" value="{{ $url }}">
                @include('components.invalid', ['name' => 'url'])
            </div>

            <div class="col-md-12">
                <div class="p-2 rounded row d-flex justify-content-center">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#acceptModal">
                        সংরক্ষণ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">সব ঠিক আছে তো?</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                    <button type="submit" class="btn btn-success" form="update-form">হ্যাঁ</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('frontend.ad.update', $ad->id) }}" id="update-form" method="post"
          enctype="multipart/form-data">
        {{ method_field('put') }}
        {{ csrf_field() }}
    </form>
@endsection
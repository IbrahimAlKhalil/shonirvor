@extends('layouts.backend.master')

@section('title', 'স্লাইডার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/contents/slider.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <div class="row mb-4">
                    <form action="{{ route('contents.slider.update',  $id) }}"
                          method="post" enctype="multipart/form-data">
                        <ul class="list-group w-100" id="image-list">
                            @foreach($sliders as $slider)
                                <li class="list-group-item mb-2 shadow-sm" data-repeater-clone="true">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <img src="{{ asset('storage/'. $slider['image']) }}"
                                                         class="rounded img-fluid slider-image">
                                                </div>

                                                <div class="col-md-6">
                                                    <input name="images[image-{{ $loop->iteration-1 }}][link]"
                                                           type="url"
                                                           class="form-control w-100 mb-2 action-link"
                                                           placeholder="লিঙ্ক"
                                                           value="{{ $slider['link'] }}">
                                                    <input type="hidden"
                                                           name="images[image-{{ $loop->iteration-1 }}][prev-image]"
                                                           value="{{ $slider['image'] }}">
                                                    <input type="hidden"
                                                           name="images[image-{{ $loop->iteration-1 }}][id]"
                                                           value="{{ $slider['id'] }}">
                                                    <div>
                                                        <button class="btn btn-primary change-image" type="button">ছবি
                                                            পরিবর্তন
                                                            করুন
                                                        </button>
                                                        <input name="sliders[image-{{ $loop->iteration-1 }}]"
                                                               type="file"
                                                               class="form-control-file w-100 image-field"
                                                               accept="image/*">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <a class="fa fa-trash float-right text-danger delete-image remove-btn"
                                               href="#" data-content-id="{{ $slider['id'] }}"></a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        {{ method_field('put') }}
                        {{ csrf_field() }}
                        <button type="button" class="btn btn-light float-left shadow-sm" id="add-new"><i
                                    class="fa fa-plus"></i> নতুন
                            যুক্ত করুন
                        </button>
                        <button type="submit" class="btn btn-success float-right">সাবমিট</button>
                    </form>
                </div>

                <form method="post" action="" data-action="{{ route('contents.slider.index') }}" id="delete-form">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                </form>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
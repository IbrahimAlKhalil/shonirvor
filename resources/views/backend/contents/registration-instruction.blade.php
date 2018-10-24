@extends('layouts.backend.master')

@section('title', 'রেজিস্ট্রেশন নির্দেশিকা')

@section('webpack')
    <script src="{{ asset('assets/js/backend/contents/registration-instruction.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                @include('components.success')
            </div>
            <div class="col-md-9">
                <form method="post"
                      action="{{ route('contents.registration-instruction.update', $id) }}">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <textarea id="registration-instruction"
                              name="data">{{ $content->data }}</textarea>
                </form>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
            </div>
        </div>
    </div>
@endsection
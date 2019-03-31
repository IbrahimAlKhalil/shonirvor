@extends('layouts.auth.master')

@section('title', 'রেজিস্ট্রেশান')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">রেজিস্ট্রেশান</div>
                    <div class="card-body">
                        <form action="{{ route('register') }}" method="post" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">নাম<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" id="name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" required>
                                    @include('components.invalid', ['name' => 'name'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="mobile" class="col-sm-4 col-form-label">মোবাইল নাম্বার<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" id="mobile" name="mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="01xxxxxxxxx" value="{{ old('mobile') }}" required>
                                    @include('components.invalid', ['name' => 'mobile'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label">পাসওয়ার্ড<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="password" id="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-sm-4 col-form-label">পাসওয়ার্ডটি পুনরায় দিন <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                                    @include('components.invalid', ['name' => 'password'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-4 col-sm-8">
                                    <button type="submit" class="btn btn-primary">সাবমিট</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

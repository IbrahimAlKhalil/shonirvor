@extends('layouts.auth.master')

@section('title', 'লগিন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">লগিন</div>
                <div class="card-body">
                    <form action="{{ route('login') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="remember" checked>
                        <div class="form-group row">
                            <label for="mobile" class="col-sm-3 col-form-label">মোবাইল নাম্বার<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" id="mobile" name="mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="01xxxxxxxxx" value="{{ old('mobile') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 col-form-label">পাসওয়ার্ড<span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <input type="password" id="password" name="password" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                                @include('components.invalid', ['name' => 'mobile'])
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">সাবমিট</button>
                                <a class="btn btn-link" href="{{ route('password.request') }}">পাসওয়ার্ড ভুলে গেছেন?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
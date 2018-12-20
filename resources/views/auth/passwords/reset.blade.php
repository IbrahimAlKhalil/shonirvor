@extends('layouts.auth.master')

@section('title', 'পাসওয়ার্ড রিসেট')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">পাসওয়ার্ড রিসেট</div>
                    <div class="card-body">
                        @if(session()->has('message'))
                            <div class="alert alert-info text-center rounded">
                                {!! session()->get('message') !!}
                            </div>
                        @endif

                        <form action="{{ route('password.reset', request()->route('user')) }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="code" class="col-sm-4 col-form-label">SMS কোড<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" id="code" name="code"
                                           class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"
                                           value="{{ old('code') }}" placeholder="xxxxxx" required>
                                    @include('components.invalid', ['name' => 'code'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label">নতুন পাসওয়ার্ড<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="password" id="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password-confirm" class="col-sm-4 col-form-label">পাসওয়ার্ডটি পুনরায় দিন<span class="text-danger">*</span></label>
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

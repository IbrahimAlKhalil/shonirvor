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
                        <form action="{{ route('password.request') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="mobile" class="col-sm-3 col-form-label">মোবাইল নাম্বার<span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" id="mobile" name="mobile"
                                           class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                           value="{{ old('mobile') }}" placeholder="01xxxxxxxxx" required>
                                    @include('components.invalid', ['name' => 'mobile'])
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-3 col-sm-9">
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

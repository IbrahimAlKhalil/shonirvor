@extends('layouts.frontend.master')

@section('title', 'ভেরিফিকেশন')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">মোবাইল ভেরিফিকেশন</div>
                    <div class="card-body">
                        <div class="alert alert-info text-small" role="alert">
                            আপনার {{ en2bnNumber($edit->mobile) }} এই নাম্বারে একটি ভেরিফিকেশন কোড পাঠানো হয়েছে।
                        </div>
                        <form action="{{ route('profile.mobile-verification.store', $profile->id) }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="verification" class="col-sm-4 col-form-label">ভেরিফিকেশন কোড <span
                                            class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="number" id="verification" name="verification"
                                           class="form-control{{ session()->has('verification-mismatch') ? ' is-invalid' : '' }}"
                                           value="{{ old('verification') }}" placeholder="xxxxxx" required>
                                    @if(session()->has('verification-mismatch'))
                                        <span class="invalid-feedback">
                                            <strong>{{ session()->get('verification-mismatch') }}</strong>
                                        </span>
                                    @endif
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
@extends('layouts.frontend.master')

@section('title', $profile->name)

@section('webpack')
    <script src="{{ asset('assets/js/frontend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/frontend/profile/edit.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container bg-white rounded mt-lg-4">
        <div class="row my-5 justify-content-md-center">
            <div class="col-md-9">
                <h3 class="mb-3">{{ $profile->name }}
                    <small class="text-muted">edit your information</small>
                </h3>
            </div>
            <div class="w-100"></div>
            <div class="col-md-3">
                <input type="file" class="file-picker" accept="image/*" name="photo" form="edit-form"
                       data-image="{{ asset('storage/'.$profile->photo) }}"
                       max="800"
                       data-error="@if($errors->has('photo')) {{ $errors->first('photo') }} @endif">
            </div>
            <div class="col-md-6">
                <form action="{{ route('profile.update', $profile->id) }}" method="post" id="edit-form"
                      enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <table class="table table-striped table-hover">
                        <tbody>
                        <tr>
                            <th scope="row">
                                <label for="name">নামঃ</label>
                            </th>
                            <td>
                                <input type="text" name="name" class="form-control" id="name"
                                       value="{{ $profile->name }}">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="mobile">মোবাইলঃ</label>
                            </th>
                            <td>
                                <input type="number" name="mobile" class="form-control" id="mobile"
                                       value="{{ $profile->mobile }}">
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label for="address">ঠিকানাঃ</label>
                            </th>
                            <td>
                                <textarea name="address" id="address" class="form-control"
                                          rows="3">{{ $profile->address }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="old-password">পুরাতন পাসওয়ার্ডঃ</label>
                            </th>
                            <td>
                                <input type="password" name="old-password" class="form-control" id="old-password">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="password">নতুন পাসওয়ার্ডঃ</label>
                            </th>
                            <td>
                                <input type="password" name="password" class="form-control" id="password">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="password_confirmation">পাসওয়ার্ডটি পুনরায় দিন <span class="text-danger">*</span></label>
                            </th>
                            <td>
                                <input type="password" name="password_confirmation" class="form-control"
                                       id="password_confirmation">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </form>
                <div class="text-center">
                    <button type="submit" form="edit-form" class="btn btn-outline-info">সাবমিট</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest(\App\Http\Requests\UpdateProfile::class, '#edit-form') !!}
@endsection
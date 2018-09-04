@extends('layouts.frontend.master')

@section('title', 'Organization Service Provider Registration')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        @include('components.success')


        <form method="post" enctype="multipart/form-data"
              action="{{ route('organization-service-registration.store') }}">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">Your Name</label>
                <div class="col-8">
                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                           class="form-control @if($errors->has('name')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile" class="col-4 col-form-label">Mobile Number</label>
                <div class="col-8">
                    <input id="mobile" name="mobile" type="number" value="{{ old('mobile') }}"
                           class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'mobile'])
                </div>
            </div>

            <div class="form-group row">
                <label for="org-name" class="col-4 col-form-label">Organization Name</label>
                <div class="col-8">
                    <input id="org-name" name="org-name" type="text" value="{{ old('org-name') }}"
                           class="form-control @if($errors->has('org-name')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'org-name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-4 col-form-label">Description</label>
                <div class="col-8">
                    <textarea rows="8" id="description" name="description" type="text"
                           class="form-control @if($errors->has('description')) is-invalid @endif">{{ old('description') }}</textarea>
                    @include('components.invalid', ['name' => 'description'])
                </div>
            </div>


            <div class="form-group row">
                <label for="personal-email" class="col-4 col-form-label">Personal Email</label>
                <div class="col-8">
                    <input id="personal-email" name="personal-email" type="text" value="{{ old('personal-email') }}"
                           class="form-control @if($errors->has('personal-email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'personal-email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Organization Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" value="{{ old('email') }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="age" class="col-4 col-form-label">Age</label>
                <div class="col-8">
                    <input id="age" name="age" type="number" value="{{ old('age') }}" required="required"
                           class="form-control @if($errors->has('age')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'age'])
                </div>
            </div>

            <div class="form-group row">
                <label for="nid" class="col-4 col-form-label">NID Number</label>
                <div class="col-8">
                    <input id="nid" name="nid" type="number" value="{{ old('nid') }}"
                           class="form-control @if($errors->has('nid')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'nid'])
                </div>
            </div>

            <div class="form-group row">
                <label for="latitude" class="col-4 col-form-label">Latitude</label>
                <div class="col-8">
                    <input id="latitude" name="latitude" type="number" value="{{ old('latitude') }}"
                           class="form-control @if($errors->has('latitude')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'latitude'])
                </div>
            </div>

            <div class="form-group row">
                <label for="longitude" class="col-4 col-form-label">Longitude</label>
                <div class="col-8">
                    <input id="longitude" name="longitude" type="number" value="{{ old('longitude') }}"
                           class="form-control @if($errors->has('longitude')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'longitude'])
                </div>
            </div>

            <div class="form-group row">
                <label for="service" class="col-4 col-form-label">Service</label>
                <div class="col-8">
                    <input id="service" name="service" type="text" value="{{ old('service') }}"
                           class="form-control @if($errors->has('service')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'service'])
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Address</label>
                <div class="col-8">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">
                        {{ old('address') }}
                    </textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>

            <div class="form-group row">
                <label for="photo" class="col-4 col-form-label">Profile Picture</label>
                <div class="col-8">
                    <input id="photo" name="photo" type="file" accept="image/*"
                           class="form-control @if($errors->has('photo')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'photo'])
                </div>
            </div>
            <div class="form-group row">
                <label for="images" class="col-4 col-form-label">Portfolio</label>
                <div class="col-8">
                    <input id="images" name="images[]" type="file" accept="image/*"
                           class="form-control @if($errors->has('images')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'images'])
                </div>
            </div>

            <div class="form-group row">
                <label for="docs" class="col-4 col-form-label">Documents/Papers</label>
                <div class="col-8">
                    <input id="docs" name="docs[]" type="file" accept="image/*" class="form-control" multiple>
                </div>
            </div>

            <div class="form-group row">
                <div class="offset-4 col-8">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
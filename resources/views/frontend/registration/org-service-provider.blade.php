@extends('layouts.frontend.master')

@section('title', 'Organization Service Provider Registration')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        @include('components.success')


        <form method="post" enctype="multipart/form-data" action="{{ route('registration.organization.store') }}">
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">Organization Name</label>
                <div class="col-8">
                    <input id="name" name="name" type="text" value="{{ old('email') }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" value="{{ old('email') }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
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
                    <textarea id="address" rows="8" name="address" required="required" class="form-control @if($errors->has('address')) is-invalid @endif">{{ old('address') }}</textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>
            <div class="form-group row">
                <label for="photo" class="col-4 col-form-label">Photos</label>
                <div class="col-8">
                    <input id="photo" name="photos[]" type="file" accept="image/*"
                           class="form-control @if($errors->has('photos')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'photos'])
                </div>
            </div>

            <div class="form-group row">
                <label for="documents" class="col-4 col-form-label">Documents/Papers</label>
                <div class="col-8">
                    <input id="documents" name="documents[]" type="file" accept="image/*" class="form-control" multiple>
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
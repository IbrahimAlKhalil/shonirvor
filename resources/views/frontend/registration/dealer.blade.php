@extends('layouts.frontend.master')

@section('title', 'Dealer Registrarion')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">
        @include('components.success')


        <form method="post" enctype="multipart/form-data" action="{{ route('registration.dealer.store') }}">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Email</label>
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
                <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
                <div class="col-8">
                    <input id="qualification" name="qualification" type="text" class="form-control here">
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
                <label for="photo" class="col-4 col-form-label">Photo</label>
                <div class="col-8">
                    <input id="photo" name="photo" type="file" accept="image/*"
                           class="form-control @if($errors->has('photo')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'photo'])
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
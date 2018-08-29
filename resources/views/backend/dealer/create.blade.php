@extends('layouts.backend.master')

@section('title', 'Create Dealers')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dealer.index') }}">All</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dealer.create') }}">Create</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3 class="mb-4">Create Dealer</h3>

                @if(count($errors))
                    <div class="alert alert-danger text-center">Validation Fail. Check your information.</div>
                @endif

                <form action="{{ route('dealer.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="name" class="col-4 col-form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                            @include('components.invalid', ['name' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-4 col-form-label">Mobile Number <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="mobile" name="mobile" type="text" pattern="\d*" maxlength="11" minlength="11" value="{{ old('mobile') }}" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                            @include('components.invalid', ['name' => 'mobile'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input id="email" name="email" type="text" value="{{ old('email') }}" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            @include('components.invalid', ['name' => 'email'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nid" class="col-4 col-form-label">NID Number</label>
                        <div class="col-8">
                            <input id="nid" name="nid" type="number" value="{{ old('nid') }}" class="form-control{{ $errors->has('nid') ? ' is-invalid' : '' }}">
                            @include('components.invalid', ['name' => 'nid'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-4 col-form-label">Age</label>
                        <div class="col-8">
                            <input id="age" name="age" type="number" value="{{ old('age') }}" class="form-control{{ $errors->has('age') ? ' is-invalid' : '' }}" min="11">
                            @include('components.invalid', ['name' => 'age'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
                        <div class="col-8">
                            <input id="qualification" name="qualification" type="text" value="{{ old('qualification') }}" class="form-control{{ $errors->has('qualification') ? ' is-invalid' : '' }}">
                            @include('components.invalid', ['name' => 'qualification'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-4 col-form-label">Address</label>
                        <div class="col-8">
                            <textarea name="address" id="address" rows="5" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}">{{ old('address') }}</textarea>
                            @include('components.invalid', ['name' => 'address'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-4 col-form-label">Dealer's Password <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                            @include('components.invalid', ['name' => 'password'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-4 col-form-label">Dealer's Photo</label>
                        <div class="col-8">
                            <input id="photo" name="photo" type="file" class="form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}">
                            @include('components.invalid', ['name' => 'photo'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="documents" class="col-4 col-form-label">Documents/Papers</label>
                        <div class="col-8">
                            <input id="documents" name="documents[]" type="file" class="form-control{{ $errors->has('documents.0') ? ' is-invalid' : '' }}" multiple>
                            @include('components.invalid', ['name' => 'documents.0'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="offset-4 col-8">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
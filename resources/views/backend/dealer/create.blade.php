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

                <form action="{{ route('dealer.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="name" class="col-4 col-form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="name" name="name" type="text" class="form-control @if($errors->has('name')){{ 'is-invalid' }}@endif" required>
                            @include('components.invalid', ['name' => 'name'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-4 col-form-label">Mobile Number <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="mobile" name="mobile" type="number" class="form-control @if($errors->has('mobile')){{ 'is-invalid' }}@endif" required>
                            @include('components.invalid', ['name' => 'mobile'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input id="email" name="email" type="text" class="form-control @if($errors->has('email')){{ 'is-invalid' }}@endif">
                            @include('components.invalid', ['name' => 'email'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nid" class="col-4 col-form-label">NID Number</label>
                        <div class="col-8">
                            <input id="nid" name="nid" type="number" class="form-control @if($errors->has('nid')){{ 'is-invalid' }}@endif">
                            @include('components.invalid', ['name' => 'nid'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-4 col-form-label">Age</label>
                        <div class="col-8">
                            <input id="age" name="age" type="number" class="form-control @if($errors->has('age')){{ 'is-invalid' }}@endif" min="11">
                            @include('components.invalid', ['name' => 'age'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
                        <div class="col-8">
                            <input id="qualification" name="qualification" type="text" class="form-control @if($errors->has('qualification')){{ 'is-invalid' }}@endif">
                            @include('components.invalid', ['name' => 'qualification'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-4 col-form-label">Address</label>
                        <div class="col-8">
                            <textarea name="address" id="address" rows="5" class="form-control @if($errors->has('address')){{ 'is-invalid' }}@endif"></textarea>
                            @include('components.invalid', ['name' => 'address'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-4 col-form-label">Dealer's Password <span class="text-danger">*</span></label>
                        <div class="col-8">
                            <input id="password" name="password" type="text" class="form-control @if($errors->has('password')){{ 'is-invalid' }}@endif" required>
                            @include('components.invalid', ['name' => 'password'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-4 col-form-label">Dealer's Photo</label>
                        <div class="col-8">
                            <input id="photo" name="photo" type="file" class="form-control @if($errors->has('photo')){{ 'is-invalid' }}@endif">
                            @include('components.invalid', ['name' => 'photo'])
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="documents" class="col-4 col-form-label">Documents/Papers</label>
                        <div class="col-8">
                            <input id="documents" name="documents[]" type="file" class="form-control @if($errors->has('documents.0')){{ 'is-invalid' }}@endif" multiple>
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
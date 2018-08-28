@extends('layouts.frontend.master')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        @include('components.success')

        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                <strong>Error! </strong> {{ $error }}
            </div>
        @endforeach

        <form method="post" enctype="multipart/form-data" action="{{ route('dealer-registration.store') }}">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">Name</label>
                <div class="col-8">
                    <input id="name" name="name" type="text" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="number" class="col-4 col-form-label">Mobile Number</label>
                <div class="col-8">
                    <input id="number" name="mobile" type="text" min="10" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" class="form-control here">
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-4 col-form-label">Password</label>
                <div class="col-8">
                    <input id="password" name="password" type="password" class="form-control here" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="age" class="col-4 col-form-label">Age</label>
                <div class="col-8">
                    <input id="age" name="age" type="number" required="required" class="form-control here">
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
                    <input id="nid" name="nid" type="number" class="form-control here" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Address</label>
                <div class="col-8">
                    <textarea id="address" name="address" required="required" class="form-control here">
                    </textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="photo" class="col-4 col-form-label">Photo</label>
                <div class="col-8">
                    <input id="photo" name="photo" type="file" class="form-control here">
                </div>
            </div>

            <div class="form-group row">
                <label for="documents" class="col-4 col-form-label">Documents/Papers</label>
                <div class="col-8">
                    <input id="documents" name="documents[]" type="file" class="form-control here" multiple>
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
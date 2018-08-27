@extends('layouts.frontend.master')

@section('content')
    <div style="margin-top: 40px;"></div>
    <div class="container">
        <form action="{{ route('dealer-registration.store') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="name" class="col-4 col-form-label">Name</label>
                <div class="col-8">
                    <input id="name" name="name" type="text" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="number" class="col-4 col-form-label">Phone Number</label>
                <div class="col-8">
                    <input id="number" name="number" type="text" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="age" class="col-4 col-form-label">Age</label>
                <div class="col-8">
                    <input id="age" name="age" type="text" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
                <div class="col-8">
                    <input id="qualification" name="qualification" type="text" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Address</label>
                <div class="col-8">
                    <input id="address" name="address" type="text" required="required" class="form-control here">
                </div>
            </div>
            <div class="form-group row">
                <label for="photo" class="col-4 col-form-label">Photo</label>
                <div class="col-8">
                    <input id="photo" name="photo" type="file" class="form-control here">
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
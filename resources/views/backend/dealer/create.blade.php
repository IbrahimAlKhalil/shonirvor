@extends('layouts.backend.master')

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
                        <label for="name" class="col-4 col-form-label">Name</label>
                        <div class="col-8">
                            <input id="name" name="name" type="text" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="mobile" class="col-4 col-form-label">Mobile Number</label>
                        <div class="col-8">
                            <input id="mobile" name="mobile" type="number" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-4 col-form-label">Email</label>
                        <div class="col-8">
                            <input id="email" name="email" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="age" class="col-4 col-form-label">Age</label>
                        <div class="col-8">
                            <input id="age" name="age" type="number" required="required" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
                        <div class="col-8">
                            <input id="qualification" name="qualification" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="address" class="col-4 col-form-label">Address</label>
                        <div class="col-8">
                            <textarea name="address" id="address" rows="5" required class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="photo" class="col-4 col-form-label">Photo</label>
                        <div class="col-8">
                            <input id="photo" name="photo" type="file" class="form-control">
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
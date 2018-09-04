@extends('layouts.frontend.master')

@section('title', $profile->name)

@section('content')
    <div class="container">
        <div class="row my-5 justify-content-md-center">
            <div class="col-md-9">
                <h3 class="mb-3">{{ $profile->name }} <small class="text-muted">edit your information</small></h3>
            </div>
            <div class="w-100"></div>
            <div class="col-md-3">
                <img src="{{ asset('storage/'.$profile->photo) }}" class="img-responsive img-thumbnail"
                     alt="Profile Picture">
            </div>
            <div class="col-md-6">
                <form action="{{ route('profile.update', $profile->id) }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}
                    <table class="table table-hover">
                        <caption>
                            <button type="submit" class="btn btn-outline-info">Save</button>
                        </caption>
                        <tbody>
                            <tr>
                                <th>
                                    <label for="name">Name</label>
                                </th>
                                <td>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $profile->name }}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="mobile">Mobile</label>
                                </th>
                                <td>
                                    <input type="number" name="mobile" class="form-control" id="mobile" value="{{ $profile->mobile }}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="email">Email</label>
                                </th>
                                <td>
                                    <input type="email" name="email" class="form-control" id="email" value="{{ $profile->email }}">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="address">Shipping Address</label>
                                </th>
                                <td>
                                    <textarea name="address" id="address" class="form-control" rows="3">{{ $profile->address }}</textarea>
                                </td>
                            </tr>
                            <tr class="border-bottom">
                                <th>
                                    <label for="photo">Change Photo</label>
                                </th>
                                <td>
                                    <input type="file" name="photo" id="photo" class="form-control-file">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
@endsection
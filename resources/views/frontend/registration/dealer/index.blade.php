@extends('layouts.frontend.master')

@section('title', 'Dealer Registrarion')

@section('content')
    <div class="container">
        @include('components.success')
        <h4 class="my-5">Dealer Registration Form:</h4>

        <form method="post" enctype="multipart/form-data" action="{{ route('dealer-registration.store') }}">
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="mobile" class="col-4 col-form-label">Public Mobile</label>
                <div class="col-8">
                    <input id="mobile" name="mobile" type="number" value="{{ old('mobile') ? old('mobile') : $user->mobile }}" class="form-control @if($errors->has('mobile')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'mobile'])
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Public Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" value="{{ old('email') ? old('email') : $user->email }}" class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
                </div>
            </div>
            <div class="form-group row">
                <label for="age" class="col-4 col-form-label">Age</label>
                <div class="col-8">
                    <input id="age" name="age" type="number" value="{{ old('age') }}" class="form-control @if($errors->has('age')) is-invalid @endif">
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
                    <input id="nid" name="nid" type="number" value="{{ old('nid') }}" class="form-control @if($errors->has('nid')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'nid'])
                </div>
            </div>
            <div class="form-group row">
                <label for="category" class="col-4 col-form-label">Categories</label>
                <div class="col-8">
                    <textarea id="category" name="category" placeholder="Type your product categories. Like: Shirt, Pant, Electronics, Etc.." class="form-control @if($errors->has('category')) is-invalid @endif">{{ old('category') }}</textarea>
                    @include('components.invalid', ['name' => 'category'])
                </div>
            </div>
            <div class="form-group row">
                <label class="col-4 col-form-label">Area</label>
                <div class="col-8">
                    <div class="row">
                        <div class="col-md">
                            <select name="district" class="form-control">
                                <option value="">-- Select District --</option>
                                <option value="1">A</option>
                                <option value="2">B</option>
                                <option value="3">C</option>
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="thana" class="form-control">
                                <option value="">-- Select Thana --</option>
                                <option value="1">A</option>
                                <option value="2">B</option>
                                <option value="3">C</option>
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" class="form-control">
                                <option value="">-- Select Union --</option>
                                <option value="1">A</option>
                                <option value="2">B</option>
                                <option value="3">C</option>
                            </select>
                        </div>
                        <div class="w-100 mb-2"></div>
                        <div class="col-md">
                            <input name="no_area" type="checkbox" value="1" id="no-area">
                            <label class="form-check-label" for="no-area">My area is not listed here.</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Full Work Address</label>
                <div class="col-8">
                    <textarea id="address" rows="8" name="address" class="form-control @if($errors->has('address')) is-invalid @endif">{{ old('address') }}</textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>
            @if($user->photo == 'default/user-photos/person.jpg')
            <div class="form-group row">
                <label for="photo" class="col-4 col-form-label">Profile Picture</label>
                <div class="col-8">
                    <input id="photo" name="photo" type="file" accept="image/*" class="form-control @if($errors->has('photo')) is-invalid @endif" multiple>
                    @include('components.invalid', ['name' => 'photo'])
                </div>
            </div>
            @endif
            <div class="form-group row">
                <label for="documents" class="col-4 col-form-label">Documents/Papers</label>
                <div class="col-8">
                    <input id="documents" name="documents[]" type="file" accept="image/*" class="form-control{{ $errors->has('documents.0') ? ' is-invalid' : '' }}" multiple>
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
@endsection
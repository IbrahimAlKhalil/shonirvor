@extends('layouts.frontend.master')

@section('title', 'Organization Service Provider Registration')

@section('content')
    <div style="margin-top: 40px;"></div>

    <div class="container">

        <h3>Edit Your Information</h3>

        @include('components.success')

        <form method="post" enctype="multipart/form-data"
              action="{{ route('organization-service-registration.update', $pendingOrgService->id) }}">
            {{ method_field('put') }}
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="mobile" class="col-4 col-form-label">Mobile Number</label>
                <div class="col-8">
                    <input id="mobile" name="mobile" type="number" value="{{ oldOrData('mobile', $pendingOrgService->mobile) }}"
                           class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" required>
                    @include('components.invalid', ['name' => 'mobile'])
                </div>
            </div>

            <div class="form-group row">
                <label for="org-name" class="col-4 col-form-label">Organization Name</label>
                <div class="col-8">
                    <input id="org-name" name="org-name" type="text" value="{{ oldOrData('org-name', $pendingOrgService->org_name) }}"
                           class="form-control @if($errors->has('org-name')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'org-name'])
                </div>
            </div>

            <div class="form-group row">
                <label for="description" class="col-4 col-form-label">Description</label>
                <div class="col-8">
                    <textarea rows="8" id="description" name="description" type="text"
                              class="form-control @if($errors->has('description')) is-invalid @endif">{{ oldOrData('description', $pendingOrgService->description) }}</textarea>
                    @include('components.invalid', ['name' => 'description'])
                </div>
            </div>


            <div class="form-group row">
                <label for="personal-email" class="col-4 col-form-label">Personal Email</label>
                <div class="col-8">
                    <input id="personal-email" name="personal-email" type="text" value="{{ oldOrData('personal-email', $pendingOrgService->user->email) }}"
                           class="form-control @if($errors->has('personal-email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'personal-email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-4 col-form-label">Organization Email</label>
                <div class="col-8">
                    <input id="email" name="email" type="text" value="{{ oldOrData('email', $pendingOrgService->email) }}"
                           class="form-control @if($errors->has('email')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'email'])
                </div>
            </div>

            <div class="form-group row">
                <label for="website" class="col-4 col-form-label">Website</label>
                <div class="col-8">
                    <input id="website" name="website" type="url"
                           value="{{ oldOrData('website', $pendingOrgService->website) }}"
                           class="form-control @if($errors->has('website')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'website'])
                </div>
            </div>

            <div class="form-group row">
                <label for="facebook" class="col-4 col-form-label">Facebook</label>
                <div class="col-8">
                    <input id="facebook" name="facebook" type="url"
                           value="{{ oldOrData('facebook', $pendingOrgService->facebook) }}"
                           class="form-control @if($errors->has('facebook')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'facebook'])
                </div>
            </div>


            <div class="form-group row">
                <label class="col-4 col-form-label">Area</label>
                <div class="col-8">
                    <div class="row">
                        <div class="col-md">
                            <select name="district" class="form-control">
                                <option value="">-- Select District --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" {{ selectOpt($pendingOrgService->district->id, $district->id) }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="thana" class="form-control">
                                <option value="">-- Select Thana --</option>
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}" {{ selectOpt($pendingOrgService->thana->id, $thana->id) }}>{{ $thana->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md">
                            <select name="union" class="form-control">
                                <option value="">-- Select Union --</option>
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" {{ selectOpt($pendingOrgService->union->id, $union->id) }}>{{ $union->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100 mb-2"></div>
                        <div class="col-md">
                            <label class="form-check-label" for="no-area">My area is not listed here.</label>
                            <input name="no_area" type="checkbox" value="1" id="no-area" {{ checkBox($pendingOrgService->no_area) }}>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Address</label>
                <div class="col-8">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ oldOrData('address', $pendingOrgService->address) }}</textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>

            <div class="form-group row">
                <label for="category" class="col-4 col-form-label">Service Category</label>
                <div class="col-8">
                    <textarea id="category" name="category"
                              class="form-control @if($errors->has('category')) is-invalid @endif" rows="4" placeholder="Type your service categories. Like: Teacher, Doctor, Electrician, Etc..">{{ oldOrData('category', $pendingOrgService->category) }}</textarea>
                    @include('components.invalid', ['name' => 'category'])
                </div>
            </div>

            <div class="form-group row">
                <label for="age" class="col-4 col-form-label">Age</label>
                <div class="col-8">
                    <input id="age" name="age" type="number" value="{{ oldOrData('age', $pendingOrgService->user->age) }}" required="required"
                           class="form-control @if($errors->has('age')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'age'])
                </div>
            </div>

            <div class="form-group row">
                <label for="nid" class="col-4 col-form-label">NID Number</label>
                <div class="col-8">
                    <input id="nid" name="nid" type="number" value="{{ oldOrData('nid', $pendingOrgService->user->nid) }}"
                           class="form-control @if($errors->has('nid')) is-invalid @endif" required>
                    @include('components.invalid', ['name' => 'nid'])
                </div>
            </div>

            <div class="form-group row">
                <label for="address" class="col-4 col-form-label">Address</label>
                <div class="col-8">
                    <textarea id="address" rows="8" name="address" required="required"
                              class="form-control @if($errors->has('address')) is-invalid @endif">{{ oldOrData('address', $pendingOrgService->address) }}</textarea>
                    @include('components.invalid', ['name' => 'address'])
                </div>
            </div>

            <div class="form-group row">
                <label for="logo" class="col-4 col-form-label">Logo</label>
                <div class="col-8">
                    <input id="logo" name="logo" type="file" accept="image/*"
                           class="form-control @if($errors->has('logo')) is-invalid @endif">
                    @include('components.invalid', ['name' => 'logo'])
                </div>
            </div>

            @if(!$isPicExists)
                <div class="form-group row">
                    <label for="photo" class="col-4 col-form-label">Profile Picture</label>
                    <div class="col-8">
                        <input id="photo" name="photo" type="file" accept="image/*"
                               class="form-control @if($errors->has('photo')) is-invalid @endif">
                        @include('components.invalid', ['name' => 'photo'])
                    </div>
                </div>
            @endif

            <div class="form-group row">
                <label for="images" class="col-4 col-form-label">Work Photos</label>
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
@endsection
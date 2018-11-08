@extends('layouts.backend.master')

@section('title', 'ইউজার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                <h2>{{ $user->name }}</h2>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn w-100 @if($user->referPackage()->exists()){{ 'btn-success' }}@else{{ 'btn-info' }}@endif" data-toggle="modal" data-target="#referPackageModal">রেফার প্যাকেজ</button>
                        <!-- Modal -->
                        <div class="modal fade" id="referPackageModal" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">রেফার প্যাকেজ</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('backend.user.refer-package', $user->id) }}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <div class="modal-body">
                                            <select name="refer-id" class="form-control">
                                                @foreach($referPackages as $package)
                                                    @php($properties = $package->properties->groupBy('name'))
                                                    <option value="{{ $package->id }}"
                                                        @if($package->id == $userReferPackageId){{ 'selected' }}@endif>
                                                        {{ $properties['name'][0]->value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer  border-top-0">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary">সাবমিট</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
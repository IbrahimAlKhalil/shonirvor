@extends('layouts.backend.master')

@section('title', 'সার্ভিস প্যাকেজসমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                @include('components.success')
                <h3>সার্ভিস প্যাকেজসমূহ</h3>
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">বর্ণনাঃ</th>
                        <th scope="col">মেয়াদঃ</th>
                        <th scope="col">দিনঃ</th>
                        <th scope="col">মূল্যঃ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($serviceRequests as $serviceRequest)

                        @php($serial = $serviceRequests->perPage() * ($serviceRequests->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <th scope="row">{{ en2bnNumber($serial) }}</th>
                            <td>
                                <a href="{{ route('individual-service-request.show', $serviceRequest->id) }}">{{ $serviceRequest->user->name }}</a>
                            </td>
                            <td>{{ $serviceRequest->mobile }}</td>
                            <td>{{ $serviceRequest->user->age }}</td>
                            <td>{{ $serviceRequest->category->name }}</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5">কোন প্যাকেজ খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $serviceRequests->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                    <div class="col-12 mt-3">
                        <button class="w-100 btn btn-info" data-toggle="modal"
                                data-target="#create-package-modal">প্যাকেজ তৈরি করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-package-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">প্যাকেজ তৈরি করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.package.ind-service.store') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="name" class="col-3 col-form-label text-right">প্যাকেজের নামঃ</label>
                            <div class="col-9">
                                <input id="name" name="name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-3 col-form-label text-right">বর্ণনাঃ</label>
                            <div class="col-9">
                                <textarea id="description" name="description" type="text"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="duration" class="col-3 col-form-label text-right">মেয়াদঃ</label>
                            <div class="col-9 input-group">
                                <input id="duration" name="duration" type="number" class="form-control">
                                <div class="input-group-append">
                                   <span class="input-group-text" id="basic-addon2">দিনঃ</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="fee" class="col-3 col-form-label text-right">মূল্যঃ</label>
                            <div class="col-9">
                                <input id="fee" name="fee" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success">সাবমিট</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
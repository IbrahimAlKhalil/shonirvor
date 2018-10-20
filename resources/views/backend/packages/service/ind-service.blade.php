@extends('layouts.backend.master')

@section('title', 'সার্ভিস প্যাকেজসমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <nav aria-label="breadcrumb" class="col-md-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">ব্যাক্তিগত সার্ভিস প্যাকেজসমূহ</li>
                </ol>
            </nav>
            <div class="col-md-9">
                @include('components.success')
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">নাম</th>
                        <th scope="col">বর্ণনা</th>
                        <th scope="col">মেয়াদ</th>
                        <th scope="col">মূল্য</th>
                        <th scope="col">পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($packages as $key => $package)
                        @php($properties = $package->properties->groupBy('name'))
                        @php($serial = $packages->perPage() * ($packages->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <th scope="row">{{ en2bnNumber($serial) }}</th>
                            <td>{{ $properties['name'][0]->value }}</td>
                            <td>{{ $properties['description'][0]->value }}</td>
                            <td>{{ $properties['duration'][0]->value }}</td>
                            <td>{{ $properties['fee'][0]->value }}</td>
                            <td class="align-middle">
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm" data-toggle="modal"
                                   data-target="#editModal{{ $key }}">
                                    <i class="fa fa-pencil-square-o"></i> এডিট
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm" data-toggle="modal"
                                   data-target="#deleteModal{{ $key }}">
                                    <i class="fa fa-trash-o"></i> ডিলিট
                                </a>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $key }}">
                                    <div class="modal-dialog modal-dialog-center">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">প্যাকেজ তৈরি করুন</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('backend.package.org-service.update', $package->id) }}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('put') }}
                                                    <div class="form-group row">
                                                        <label for="edit-name" class="col-3 col-form-label text-right">প্যাকেজের
                                                            নামঃ</label>
                                                        <div class="col-9">
                                                            <input id="edit-name"
                                                                   name="values[{{ $properties['name'][0]->id }}]"
                                                                   type="text"
                                                                   class="form-control"
                                                                   value="{{ $properties['name'][0]->value }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="edit-description"
                                                               class="col-3 col-form-label text-right">বর্ণনাঃ</label>
                                                        <div class="col-9">
                                <textarea id="edit-description" name="values[{{ $properties['description'][0]->id }}]"
                                          type="text"
                                          class="form-control">{{ $properties['description'][0]->value }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="edit-duration"
                                                               class="col-3 col-form-label text-right">মেয়াদঃ</label>
                                                        <div class="col-9 input-group">
                                                            <input id="edit-duration"
                                                                   name="values[{{ $properties['duration'][0]->id }}]"
                                                                   type="number"
                                                                   class="form-control"
                                                                   value="{{ $properties['duration'][0]->value }}">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"
                                                                      id="basic-addon2">দিনঃ</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="edit-fee"
                                                               class="col-3 col-form-label text-right">মূল্যঃ</label>
                                                        <div class="col-9">
                                                            <input id="edit-fee"
                                                                   name="values[{{ $properties['fee'][0]->id }}]"
                                                                   type="text" class="form-control"
                                                                   value="{{ $properties['fee'][0]->value }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top-0">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">বাতিল
                                                        </button>
                                                        <button type="submit" class="btn btn-success">সাবমিট</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $key }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-dialog-center" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom-0">
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি এই
                                                    প্যাকেজটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('backend.package.org-service.destroy', $package->id) }}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">না
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">হ্যাঁ, মুছতে চাই
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">কোন প্যাকেজ খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $packages->links('pagination::bootstrap-4') }}
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
        <div class="modal-dialog modal-dialog-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">প্যাকেজ তৈরি করুন</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.package.org-service.store') }}" method="post">
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
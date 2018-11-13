@extends('layouts.backend.master')

@section('title', 'ক্যাটাগরি')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    @include('components.error')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item h5 mb-0 action">ক্যাটাগরি</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col" width="5%">#</th>
                        <th scope="col" width="30%">ছবি</th>
                        <th scope="col" width="40%">ক্যাটাগরি</th>
                        <th>পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $key => $category)
                        @php($serial = $categories->perPage() * ($categories->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td class="align-middle">{{ en2bnNumber($serial) }}</td>
                            <td class="align-middle">
                                <img class="w-25" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('individual-category.show', $category->id) }}">{{ $category->name }}</a>
                            </td>
                            <td class="align-middle">
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm" data-toggle="modal" data-target="#editModal{{ $key }}">
                                    <i class="fa fa-pencil-square-o"></i> এডিট
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $key }}">
                                    <i class="fa fa-trash-o"></i> ডিলিট
                                </a>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $key }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p class="modal-title h5">বিজ্ঞাপন এডিট করুন</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('individual-category.update', $category->id) }}" method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <div class="modal-body text-left">
                                                    <p class="text-center"><img src="{{ asset('storage/'.$category->image) }}" class="img-thumbnail img-fluid"></p>
                                                    <div class="form-group row">
                                                        <label for="edit-image-input" class="col-sm-2 col-form-label text-right">ছবি</label>
                                                        <div class="col-sm-10">
                                                            <input type="file" name="image" id="edit-image-input" class="form-control-file">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="edit-name-input" class="col-sm-2 col-form-label text-right">নাম</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="name" id="edit-name-input" class="form-control" value="{{ $category->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল করুন</button>
                                                    <button type="submit" class="btn btn-success">সাবমিট করুন</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $key }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-bottom-0">
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি এই ক্যাটাগরিটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('individual-category.destroy', $category->id) }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                                                    <button type="submit" class="btn btn-danger">হ্যাঁ, মুছতে চাই</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">কোনো ক্যাটাগরি খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">নতুন ক্যাটাগরি জুড়ুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('individual-category.store') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <label for="category" class="label">ক্যাটাগরিের নাম</label>
                                    <input id="category" class="form-control" type="text" name="category">
                                    <input type="file" accept="image/*" name="image" class="form-control-file mt-2" required>
                                    <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">সাবমিট</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
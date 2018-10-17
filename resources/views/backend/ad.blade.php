@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row">
            <div class="col-md-9">
                @include('components.success')
                <table class="table table-striped table-bordered table-hover table-sm text-center mt-3 bg-white">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">বিজ্ঞাপনের ছবি</th>
                            <th width="30%">লিঙ্ক</th>
                            <th>পদক্ষেপ</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($ads as $key => $ad)
                        @php($serial = $ads->perPage() * ($ads->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td class="align-middle">{{ en2bnNumber($serial) }}</td>
                            <td><img src="{{ asset('storage/'.$ad->image) }}" class="w-50"></td>
                            <td class="align-middle">
                                <a href="{{ $ad->url }}">বিজ্ঞাপনের লিঙ্ক</a>
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
                                                <p class="modal-title h5" id="exampleModalLabel">বিজ্ঞাপন এডিট করুন</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('backend.ad.update', $ad->id) }}" method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <div class="modal-body text-left">
                                                    <p><img src="{{ asset('storage/'.$ad->image) }}" class="img-thumbnail img-responsive"></p>
                                                    <div class="form-group row">
                                                        <label for="image" class="col-sm-2 col-form-label text-right">ছবি</label>
                                                        <div class="col-sm-10">
                                                            <input type="file" name="image" class="form-control-file">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="url" class="col-sm-2 col-form-label text-right">লিঙ্ক</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" name="url" id="url" class="form-control" value="{{ $ad->url }}">
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
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি এই বিজ্ঞাপনটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('backend.ad.destroy', $ad->id) }}" method="post">
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
                            <td colspan="4">কোন বিজ্ঞাপন নেই</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $ads->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card mt-3">
                            <h5 class="card-header">নতুন বিজ্ঞাপন তৈরি করুন</h5>
                            <div class="card-body">
                                <form method="post" action="{{ route('backend.ad.store') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="image" class="label">ছবি</label>
                                        <input id="image" name="image" type="file" class="form-control-file" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="url" class="label">লিঙ্ক</label>
                                        <input type="text" name="url" id="url" class="form-control">
                                    </div>
                                    <button class="mt-3 btn btn-secondary btn-success rounded float-right" type="submit">সাবমিট</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
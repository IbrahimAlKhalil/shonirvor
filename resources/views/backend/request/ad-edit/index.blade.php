@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপন এডিট জন্য')

@section('webpack')
    <script src="{{ asset('assets/js/backend/common.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/backend/request/ad-edit/index.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h5 mb-0">বিজ্ঞাপন এডিট রিকুয়েস্ট</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ছবি</th>
                        <th scope="col">লিঙ্ক</th>
                        <th scope="col">পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php($iteration = $adEdits->perPage() * $adEdits->currentPage() - $adEdits->perPage())
                    @forelse($adEdits  as $adEdit)
                        <tr data-edit-id="{{ $adEdit->id }}">
                            <td>{{ en2bnNumber(++$iteration) }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $adEdit->image) }}"
                                     class="img-fluid img-rounded img-thumbnail" style="width: 150px">
                            </td>
                            <td>{{ $adEdit->url }}</td>
                            <td class="align-middle">
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm accept-btn">
                                    <i class="fa fa-check"></i> গ্রহণ করুন
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm delete-btn">
                                    <i class="fa fa-trash-o"></i> ডিলিট করুন
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                কোন রিকুয়েস্ট নেই
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $adEdits->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="row">
                        <div class="col-12">
                            @include('components.side-nav')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">রিকোয়েস্টটি গ্রহণ করতে চান?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                    <button type="submit" class="btn btn-success" form="approve-form">সাবমিট</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel">সত্যিই কি আপনি রিকোয়েস্টটি মুছে ফেলতে চান?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">না</button>
                    <button type="submit" class="btn btn-danger" form="delete-form">ডিলিট</button>
                </div>
            </div>
        </div>
    </div>

    <form id="approve-form" method="post">
        {{ method_field('put') }}
        {{ csrf_field() }}
    </form>

    <form id="delete-form" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection
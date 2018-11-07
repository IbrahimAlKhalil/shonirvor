@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপন এডিট এর আবেদন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/common.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="bg-white mt-4 p-4 rounded row w-50">
            <div class="col-md-12 mb-3">
                <div class="rounded row">
                    <div class="col-md-12 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="rounded-circle shadow"
                                     style="background-image: url({{ asset('storage/' . $adEdit->ad->user->photo) }}); width: 100px; height: 100px;"></div>
                            </div>
                            <div class="col-md-9">
                                <div class="w-100 h-100 d-flex align-items-center">
                                    <a href="#">{{ $adEdit->ad->name }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0 list-group mt-4">
                        <table class="table-sm table-striped table-hover">
                            @if($adEdit->url)
                                <tr>
                                    <th scope="row">লিঙ্কঃ</th>
                                    <td>{{ $adEdit->url }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3 p-0">
                <img src="{{ asset('storage/' . $adEdit->image) }}" class="img-fluid rounded">
            </div>

            <div class="col-md-12">
                <div class="p-2 rounded row d-flex justify-content-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal">
                            গ্রহণ করুন
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                            ডিলিট করুন
                        </button>
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

    <form action="{{ route('request.ad-edit.update', $adEdit->id) }}" id="approve-form" method="post">
        {{ method_field('put') }}
        {{ csrf_field() }}
    </form>

    <form action="{{ route('request.ad-edit.destroy', $adEdit->id) }}" id="reject-form" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection
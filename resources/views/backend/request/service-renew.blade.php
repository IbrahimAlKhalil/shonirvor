@extends('layouts.backend.master')

@section('title', 'বিজ্ঞাপন')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-7 bg-white rounded shadow-sm">
                <div class="row p-5 align-items-center">
                    <div class="col-md-3">
                        <img src="{{ asset('storage/' . $application->incomeable->user->photo) }}" class="rounded-circle img-fluid">
                    </div>
                    <div class="col-md-9 h3 pl-4">
                        <a href="javascript:">{{ $application->incomeable->user->name }}</a>
                    </div>
                    <div class="col-12 mt-4">
                        <table class="table table-striped table-hover table-borderless">
                            <tr>
                                <th scope="row">প্যাকেজঃ</th>
                                <td>{{ $application->package->properties->where('name', 'name')->first()->value }}</td>
                            </tr>
                            <tr>
                                <th scope="row">পেমেন্ট মেথডঃ</th>
                                <td>{{ $application->paymentMethod->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যে নাম্বার থেকে পাঠানো হয়েছেঃ</th>
                                <td>{{ $application->from }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction ID:</th>
                                <td>{{ $application->transactionId }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptModal">
                                গ্রহণ করুন
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">
                                ডিলিট করুন
                            </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('backend.request.service-renew.update', $application->id) }}" id="approve-form" method="post">
        {{ method_field('put') }}
        {{ csrf_field() }}
    </form>
    <form action="{{ route('backend.request.service-renew.destroy', $application->id) }}" id="delete-form" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection
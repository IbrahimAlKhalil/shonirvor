@extends('layouts.frontend.master')

@section('title', 'Service Provider Registration')

@section('webpack')
    <script src="{{ asset('assets/js/frontend/home.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container my-lg-5 bg-white p-4 rounded">
        <div class="row">
            <div class="col-12">
                <h2>সার্ভিস প্রভাইডার এর নিয়মাবলী</h2>
            </div>
            <div class="col-12 mt-4">
                {!! getContent('registration-instruction')->data !!}
            </div>
            <div class="col-12 mt-3">
                <div class="row justify-content-around">
                    @php($user = \Illuminate\Support\Facades\Auth::user())
                    @php($IndPendingExists = $user->inds()->onlyPending()->exists())
                    @php($OrgPendingExists = $user->orgs()->onlyPending()->exists())
                    @php($pendingExists = $IndPendingExists || $OrgPendingExists)

                    @if(!$pendingExists)
                        <div class="col-lg-6 mb-2 mb-lg-0 text-center text-lg-right">
                            <a href="{{ route('individual-service-registration.index') }}"
                               class="btn btn-secondary btn-success" role="button">ব্যক্তিগত সার্ভিস রেজিস্ট্রেশান</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-left">
                            <a href="{{ route('organization-service-registration.index') }}"
                               class="btn btn-secondary btn-success" role="button">প্রাতিষ্ঠানিক সার্ভিস
                                রেজিস্ট্রেশান</a>
                        </div>
                    @else
                        <div class="col-lg-6 mb-2 mb-lg-0 text-center text-lg-right">
                            <a href="#warning"
                               data-toggle="modal"
                               class="btn btn-secondary btn-success" role="button">ব্যক্তিগত সার্ভিস রেজিস্ট্রেশান</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-left">
                            <a href="#warning"
                               data-toggle="modal"
                               class="btn btn-secondary btn-success" role="button">প্রাতিষ্ঠানিক সার্ভিস
                                রেজিস্ট্রেশান</a>
                        </div>
                        <div class="modal fade" id="warning" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body text-center">
                                        আপনার একটি সার্ভিস রেজিস্ট্রেশন প্রক্রিয়াধীন রয়েছে। এডমিন আপনার পূর্বের আবেদনটি
                                        গ্রহণ করলে আপনি আবার সার্ভিসের জন্য আবেদন করতে পারবেন।
                                    </div>
                                    <div class="modal-footer border-top-0 row justify-content-center">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
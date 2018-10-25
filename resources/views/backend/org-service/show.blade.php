@extends('layouts.backend.master')

@section('title', $org->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $org->logo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->logo) }}"
                                 class="img-responsive img-thumbnail" alt="{{ $org->name }}">
                        </a>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $org->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">নাম (ব্যাক্তিগত)</th>
                                <td><a href="#">{{ $org->user->name }}</a></td>
                            </tr>
                            <tr>
                                <th scope="row">মোবাইল</th>
                                <td>{{ $org->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ইমেইল</th>
                                <td>{{ $org->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $org->user->nid }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $org->district->bn_name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">থানা</th>
                                <td>{{ $org->thana->bn_name}} @if($org->thana->is_pending) <span
                                            class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif</td>
                            </tr>

                            <tr>
                                <th scope="row">ইউনিয়ন</th>
                                <td>{{ $org->union->bn_name}}  @if($org->union->is_pending) <span
                                            class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif</td>
                            </tr>
                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $org->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ক্যাটাগরি</th>
                                <td>{{ $org->category->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">সাব-ক্যাটাগরি</th>
                                <td>
                                    @foreach($org->subCategories('confirmed')->get() as $subCategory)
                                        <span class="badge badge-success">{{ $subCategory->name }}</span>&nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="btn-group mx-auto">
                                <span class="btn btn-secondary btn-warning"
                                      onclick="confirm('Are You Sure?') && document.getElementById('deactivate-account').submit()">একাউন্টটি নিষ্ক্রিয় করুন</span>
                                <span class="btn btn-secondary btn-danger rounded-right"
                                      onclick="confirm('Are You Sure?') && document.getElementById('remove-account').submit()">একাউন্টটি মুছে ফেলুন</span>

                                <form id="deactivate-account"
                                      action="{{ route('organization-service.destroy', $org->id) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" name="type" value="deactivate">
                                </form>
                                <form id="remove-account"
                                      action="{{ route('organization-service.destroy', $org->id) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" name="type" value="remove">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">নথিপত্র</h3>
                    <div class="col-md-3">
                        {{--<span class="text-muted">ট্রেড লাইসেন্স</span>--}}
                        <a href="{{ asset('storage/' . $org->trade_license) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->trade_license) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
                    @foreach($org->user->identities as $identity)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $identity->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $identity->path) }}"
                                     class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">কাজের ছবি</h3>
                    @forelse($org->workImages as $image)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->path) }}" class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <p class="text-muted col-12">কোন ছবি আপলোড করা হয়নি!</p>
                    @endforelse
                </div>
            </div>
            <div class="col-md-3">
                <div class="row mt-5">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', compact('visitor'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn w-100 @if($org->is_top){{ 'btn-success' }}@else{{ 'btn-info' }}@endif" data-toggle="modal" data-target="#isTopModal">টপ সার্ভিস</button>
                        <!-- Modal -->
                        <div class="modal fade" id="isTopModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">এই সার্ভিস প্রভাইদারকে কি টপে রাখতে চান?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('org-service.top', $org->id) }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <select name="is_top" class="form-control">
                                                <option value="1" @if($org->is_top){{ 'selected' }}@endif>হ্যাঁ</option>
                                                <option value="0" @if(!$org->is_top){{ 'selected' }}@endif>না</option>
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
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.notification', ['userId' => $org->user->id])
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.sms', ['userId' => $org->user->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
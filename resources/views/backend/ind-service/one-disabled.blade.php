@extends('layouts.backend.master')

@section('title', $ind->user->name)

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $ind->user->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $ind->user->photo) }}" class="img-responsive img-thumbnail"
                                 alt="{{ $ind->user->name }}">
                        </a>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $ind->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">নাম</th>
                                <td>{{ $ind->user->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">কাজের ফোন নম্বর</th>
                                <td>{{ $ind->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ব্যক্তিগত ফোন নম্বর</th>
                                <td>{{ $ind->user->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">কাজের ইমেইল</th>
                                <td>{{ $ind->user->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ব্যক্তিগত ইমেইল</th>
                                <td>{{ $ind->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">বয়স</th>
                                <td>{{ $ind->user->age }}</td>
                            </tr>
                            <tr>
                                <th scope="row">যোগ্যতা/অভিজ্ঞতা</th>
                                <td>{{ $ind->user->qualification }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $ind->user->nid }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $ind->district->bn_name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">থানা</th>
                                <td>{{ $ind->thana->bn_name}} @if($ind->thana->is_pending) <span
                                            class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif</td>
                            </tr>

                            <tr>
                                <th scope="row">ইউনিয়ন</th>
                                <td>{{ $ind->union->bn_name}}  @if($ind->union->is_pending) <span
                                            class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif</td>
                            </tr>
                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $ind->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">সেবা-বিভাগ</th>
                                <td>{{ $ind->category->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">সাব-ক্যাটাগরি</th>
                                <td>
                                    @foreach($ind->subCategories('confirmed')->get() as $subCategory)
                                        <span class="badge badge-success">{{ $subCategory->name }}</span>&nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="btn-group mx-auto">
                                <span class="btn btn-secondary btn-success mx-auto"
                                      onclick="confirm('Are You Sure?') && document.getElementById('activate-account').submit()">একাউন্টটি স্বষ্ক্রিয় করুন</span>
                                <span class="btn btn-secondary btn-danger rounded-right"
                                      onclick="confirm('Are You Sure?') && document.getElementById('remove-account').submit()">একাউন্টটি মুছে ফেলুন</span>

                                <form id="activate-account" action="{{ route('individual-service.activate') }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{ $ind->id }}">
                                </form>
                                <form id="remove-account" action="{{ route('individual-service.destroy', $ind->id) }}"
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
                        <span class="text-muted">অভিজ্ঞতা প্রত্যয়ন পত্র</span>
                        <a href="{{ asset('storage/' . $ind->experience_certificate) }}" target="_blank">
                            <img src="{{ asset('storage/' . $ind->experience_certificate) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">কাজের ছবি</h3>
                    @forelse($ind->workImages as $image)
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
                        @include('components.notification', ['userId' => $ind->user->id])
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.sms', ['userId' => $ind->user->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
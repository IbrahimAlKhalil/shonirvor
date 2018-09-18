@extends('layouts.backend.master')

@section('title', $org->user->name)

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $org->user->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->user->photo) }}"
                                 class="img-responsive img-thumbnail" alt="{{ $org->user->name }}">
                        </a>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $org->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">মোবাইল</th>
                                <td>{{ $org->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ইমেইল</th>
                                <td>{{ $org->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">বয়স</th>
                                <td>{{ $org->user->age }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $org->user->nid }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $org->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">সেবা-বিভাগ</th>
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
                        <span class="text-muted">ট্রেড লাইসেন্স</span>
                        <a href="{{ asset('storage/' . $org->trade_license) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->trade_license) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
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
            <div class="col-md-3 mt-5">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav', compact('navs'))
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.visitor-conuter', compact('visitor'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
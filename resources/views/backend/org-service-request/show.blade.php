@extends('layouts.backend.master')

@section('title', $serviceRequest->user->name)

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $serviceRequest->user->photo) }}">
                            <img src="{{ asset('storage/' . $serviceRequest->user->photo) }}" class="img-thumbnail"
                                 alt="{{ $serviceRequest->user->name }}">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $serviceRequest->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                            <tr>
                                <th scope="row">মোবাইল</th>
                                <td>{{ $serviceRequest->mobile }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ইমেইল</th>
                                <td>{{ $serviceRequest->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $serviceRequest->user->nid }}</td>
                            </tr>

                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $serviceRequest->district->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">থানা</th>
                                <td>{{ $serviceRequest->thana->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">ইউনিয়ন</th>
                                <td>{{ $serviceRequest->union->bn_name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $serviceRequest->address }}</td>
                            </tr>
                            <tr>
                                <th scope="row">অক্ষাংশ</th>
                                <td>{{ $serviceRequest->latitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">দ্রাঘিমাংশ</th>
                                <td>{{ $serviceRequest->longitude }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ক্যাটাগরি</th>
                                <td>{{ $serviceRequest->category->name }} @if(!$serviceRequest->category->is_confirmed)
                                        <span class="pull-right badge badge-primary">অনুরোধকৃত</span> @endif</td>
                            </tr>
                            <tr>
                                <th scope="row">সাব-ক্যাটাগরি</th>
                                <td>
                                    @forelse($serviceRequest->subCategories('confirmed')->get() as $subCategory)
                                        {{ $subCategory->name }},
                                    @empty
                                        <span class="text-muted small">No Confirmed Sub-Categories</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                            </tbody>
                        </table>

                        <form id="approve-request" action="{{ route('organization-service-request.store') }}"
                              method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                            @if(!$serviceRequest->category->is_confirmed)
                                <div class="form-group row">
                                    <label for="category" class="col-4 col-form-label">ক্যাটাগরি
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-8">
                                        <input id="category" name="category" type="text"
                                               value="{{ oldOrData('category', $serviceRequest->category->name) }}"
                                               class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }}"
                                               required>
                                        @include('components.invalid', ['name' => 'category'])
                                    </div>
                                </div>
                            @endif
                            @php($subCategories = $serviceRequest->subCategories('requested')->get())
                            @if($subCategories->count() >= 1)
                                <label>Requested Sub-Categories</label>
                                @foreach($subCategories as $key => $subCategory)
                                    <div class="form-group row">
                                        <label for="sub-categories" class="col-4 col-form-label">সাব
                                            ক্যাটাগরি {{ en2bnNumber($loop->iteration) }} <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-8">
                                            <input id="sub-categories"
                                                   name="sub-categories[{{ $loop->iteration-1 }}][name]" type="text"
                                                   value="{{ oldOrData('sub-categories.' . $key, $subCategory->name) }}"
                                                   class="form-control{{ $errors->has('sub-categories') ? ' is-invalid' : '' }}"
                                                   required>
                                            <input type="hidden" value="{{ $subCategory->id }}"
                                                   name="sub-categories[{{ $loop->iteration-1 }}][id]">
                                            @include('components.invalid', ['name' => 'sub-categories'])
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($serviceRequest->thana->is_pending)
                                <div class="form-group row">
                                    <label for="thana" class="col-4 col-form-label">থানা
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-8">
                                        <input id="thana" name="thana" type="text"
                                               value="{{ oldOrData('thana', $serviceRequest->thana->bn_name) }}"
                                               class="form-control{{ $errors->has('thana') ? ' is-invalid' : '' }}"
                                               required>
                                        @include('components.invalid', ['name' => 'thana'])
                                    </div>
                                </div>
                            @endif

                            @if($serviceRequest->union->is_pending)
                                <div class="form-group row">
                                    <label for="union" class="col-4 col-form-label">ইউনিয়ন
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-8">
                                        <input id="union" name="union" type="text"
                                               value="{{ oldOrData('union', $serviceRequest->union->bn_name) }}"
                                               class="form-control{{ $errors->has('union') ? ' is-invalid' : '' }}"
                                               required>
                                        @include('components.invalid', ['name' => 'union'])
                                    </div>
                                </div>
                            @endif
                        </form>

                        <div class="row">

                            <div class="btn-group mx-auto" role="group">
                                <span class="btn btn-secondary btn-success"
                                      onclick="document.getElementById('approve-request').submit();">গ্রহন করুন</span>
                                <span class="btn btn-secondary btn-danger rounded-right"
                                      onclick="document.getElementById('reject-request').submit();">প্রত্যাখ্যান করুন</span>
                                <form id="reject-request"
                                      action="{{ route('organization-service-request.destroy', $serviceRequest->id) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" value="{{ $serviceRequest->id }}" name="id">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">নথিপত্র</h3>
                    <div class="col-md-3">
                        <span class="text-muted">ট্রেড লাইসেন্স</span>
                        <a href="{{ asset('storage/' . $serviceRequest->trade_license) }}" target="_blank">
                            <img src="{{ asset('storage/' . $serviceRequest->trade_license) }}" class="img-responsive img-thumbnail">
                        </a>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">কাজের ছবি</h3>
                    @forelse($serviceRequest->workImages as $image)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->path) }}" class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <p class="text-muted col-12">No Image uploaded!</p>
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
                        @include('components.notification', ['userId' => $serviceRequest->user->id])
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        @include('components.sms', ['userId' => $serviceRequest->user->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
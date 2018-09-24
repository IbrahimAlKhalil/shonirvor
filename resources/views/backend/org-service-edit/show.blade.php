@extends('layouts.backend.master')

@section('title', $org->user->name)

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $data['logo']) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->user->photo) }}" class="img-responsive img-thumbnail"
                                 alt="{{ $org->user->name }}">
                        </a>
                    </div>

                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $org->user->name }}</h4>
                        <table class="table table-responsive table-striped table-bordered table-hover table-sm">
                            <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">আগের</th>
                                <th scope="col">নতুন</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">নাম</th>
                                <td>{{ $org->name }}</td>
                                <td>{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">মোবাইল নম্বর</th>
                                <td>{{ $org->mobile }}</td>
                                <td>{{ $data['mobile'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ইমেইল</th>
                                <td>{{ $org->user->email }}</td>
                                <td>{{ $data['email'] }}</td>
                            </tr>

                            <tr>
                                <th scope="row">জাতীয় পরিচয়পত্রের নম্বর</th>
                                <td>{{ $org->user->nid }}</td>
                                <td>{{ $data['nid'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">জেলা</th>
                                <td>{{ $org->district->bn_name}}</td>
                                <td>{{ $org->district->bn_name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">থানা</th>
                                <td>
                                    {{ $org->thana->bn_name}}
                                    @if($org->thana->is_pending)
                                        <span class="badge badge-primary pull-right">অনুরোধকৃত</span>
                                    @endif
                                </td>
                                <td>
                                    @if(array_key_exists('thana-request', $data) && $data['thana-request'])
                                        @php($thana = \Sandofvega\Bdgeocode\Models\Thana::find($data['thana-request']))
                                    @else
                                        @php($thana = \Sandofvega\Bdgeocode\Models\Thana::find($data['thana']))
                                    @endif
                                    {{ $thana->bn_name}}
                                    @if($thana->is_pending)
                                        <span class="badge badge-primary pull-right">অনুরোধকৃত</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">ইউনিয়ন</th>
                                <td>{{ $org->union->bn_name}}  @if($org->union->is_pending) <span
                                            class="badge badge-primary pull-right">অনুরোধকৃত</span> @endif</td>
                                <td>
                                    @if(array_key_exists('union-request', $data) && $data['union-request'])
                                        @php($union = \Sandofvega\Bdgeocode\Models\Union::find($data['union-request']))
                                    @else
                                        @php($union = \Sandofvega\Bdgeocode\Models\Union::find($data['union']))
                                    @endif
                                    {{ $union->bn_name}}
                                    @if($union->is_pending)
                                        <span class="badge badge-primary pull-right">অনুরোধকৃত</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">ঠিকানা</th>
                                <td>{{ $org->address }}</td>
                                <td>{{ $data['address'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ক্যাটাগরি</th>
                                <td>{{ $org->category->name }}</td>
                                <td>{{ $org->category->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">সাব-ক্যাটাগরি</th>
                                <td>
                                    @foreach($org->subCategories('confirmed')->get() as $subCategory)
                                        <span class="badge badge-success">{{ $subCategory->name }}</span>&nbsp;
                                    @endforeach
                                </td>
                                <td>
                                    @foreach(\App\Models\SubCategory::whereIn('id', $data['sub-categories'])->get() as $subCategory)
                                        <span class="badge badge-success">{{ $subCategory->name }}</span>&nbsp;
                                    @endforeach
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <form id="approve-request" action="{{ route('organization-service-edit.store') }}"
                              method="post">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $serviceEdit->id }}" name="id">
                            @if(array_key_exists('sub-category-requests', $data))
                                @php($subCategories = \App\Models\SubCategory::whereIn('id', $data['sub-category-requests'])->get())
                                @if($subCategories->count() >= 1)
                                    <label>অনুরোধকৃত সাব-ক্যাটাগরিসমূহঃ</label>
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
                                                <input type="hidden" name="sub-categories[{{ $loop->iteration-1 }}][id]"
                                                       value="{{ $subCategory->id }}">
                                                @include('components.invalid', ['name' => 'sub-categories'])
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                            @if(array_key_exists('thana-request', $data))
                                <div class="form-group row">
                                    <label for="thana" class="col-4 col-form-label">থানা
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-8">
                                        <input type="hidden" name="thana[id]" value="{{ $thana->id }}">
                                        <input id="thana" name="thana[name]" type="text"
                                               value="{{ oldOrData('thana', $thana->bn_name) }}"
                                               class="form-control{{ $errors->has('thana') ? ' is-invalid' : '' }}"
                                               required>
                                        @include('components.invalid', ['name' => 'thana'])
                                    </div>
                                </div>
                            @endif

                            @if(array_key_exists('union-request', $data))
                                <div class="form-group row">
                                    <label for="union" class="col-4 col-form-label">ইউনিয়ন
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-8">
                                        <input type="hidden" name="union[id]" value="{{ $union->id }}">
                                        <input id="union" name="union[name]" type="text"
                                               value="{{ oldOrData('union', $union->bn_name) }}"
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
                                      action="{{ route('organization-service-edit.destroy', $serviceEdit->id) }}"
                                      method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                    <input type="hidden" value="{{ $serviceEdit->id }}" name="id">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">নথিপত্র (আগের)</h3>
                    <div class="col-md-3">
                        <a href="{{ asset('storage/' . $org->logo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->logo) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
                    <div class="col-md-3">
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
                    <h3 class="my-4 col-12">কাজের ছবি (আগের)</h3>
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

                <div class="row">
                    <h3 class="my-4 col-12">নথিপত্র (নতুন)</h3>
                    <div class="col-md-3">
                        <a href="{{ asset('storage/' . $org->logo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $org->logo) }}"
                                 class="img-responsive img-thumbnail">
                        </a>
                    </div>
                    <div class="col-md-3">
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
                    <h3 class="my-4 col-12">কাজের ছবি (নতুন)</h3>
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
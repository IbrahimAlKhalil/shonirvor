@extends('layouts.backend.master')

@section('title', 'রেফারার প্যাকেজসমূহ')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container my-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active h5 mb-0">রেফারার প্যাকেজ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>নাম</th>
                        <th>সময়</th>
                        <th>এককালীন ইন্টারেস্ট</th>
                        <th>রিনিউ ইন্টারেস্ট</th>
                        <th>পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($packages as $key => $package)
                        @php($properties = $package->properties->groupBy('name'))
                        @php($serial = $packages->perPage() * ($packages->currentPage() - 1) + $loop->iteration)
                        <tr @if($properties['is_default'][0]->value)style="background-color: #caa31025"@endif>
                            <td class="align-middle">{{ en2bnNumber($serial) }}</td>
                            <td class="align-middle">{{ $properties['name'][0]->value }}</td>
                            <td class="align-middle">{{ $properties['duration'][0]->value ? en2bnNumber($properties['duration'][0]->value.' দিন') : '-' }}</td>
                            <td class="align-middle">{{ en2bnNumber($properties['refer_onetime_interest'][0]->value) }}%</td>
                            <td class="align-middle">{{ $properties['refer_renew_interest'][0]->value ? en2bnNumber($properties['refer_renew_interest'][0]->value.'%') : '-' }}</td>
                            <td class="align-middle">
                                <a href="javascript:" class="mr-2 btn btn-outline-info btn-sm" data-toggle="modal" data-target="#editModal{{ $key }}">
                                    <i class="fa fa-pencil-square-o"></i> এডিট
                                </a>
                                <a href="javascript:" class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#deleteModal{{ $key }}">
                                    <i class="fa fa-trash-o"></i> ডিলিট
                                </a>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $key }}">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <p class="modal-title h5">{{ $properties['name'][0]->value }} প্যাকেজটি এডিট করুন</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('backend.package.referrer.update', $package->id) }}" method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="edit-name{{ $key }}" class="col-3 col-form-label text-right">নাম:</label>
                                                                <div class="col-9">
                                                                    <input type="text"
                                                                           name="name"
                                                                           class="form-control"
                                                                           id="edit-name{{ $key }}"
                                                                           value="{{ $properties['name'][0]->value }}"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="edit-description{{ $key }}" class="col-3 col-form-label text-right">বর্ণনা:</label>
                                                                <div class="col-9">
                                                                    <textarea name="description" id="edit-description{{ $key }}" class="form-control">{{ $properties['description'][0]->value }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="edit-duration{{ $key }}" class="col-3 col-form-label text-right">সময়:</label>
                                                                <div class="col-9">
                                                                    <div class="input-group">
                                                                        <input type="number"
                                                                               name="duration"
                                                                               class="form-control"
                                                                               id="edit-duration{{ $key }}"
                                                                               value="{{ $properties['duration'][0]->value }}"
                                                                               placeholder="ইংরেজিতে লিখুন">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">দিন</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row align-items-center">
                                                                <label for="edit-refer-onetime-interest{{ $key }}" class="col-3 col-form-label text-right">এককালীন ইন্টারেস্ট:</label>
                                                                <div class="col-9">
                                                                    <div class="input-group">
                                                                        <input type="number"
                                                                               name="refer_onetime_interest"
                                                                               class="form-control"
                                                                               id="edit-refer-onetime-interest{{ $key }}"
                                                                               value="{{ $properties['refer_onetime_interest'][0]->value }}"
                                                                               placeholder="ইংরেজিতে লিখুন"
                                                                               required>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row align-items-center">
                                                                <label for="edit-refer-renew-interest{{ $key }}" class="col-3 col-form-label text-right">রিনিউ ইন্টারেস্ট:</label>
                                                                <div class="col-9">
                                                                    <div class="input-group">
                                                                        <input type="number"
                                                                               name="refer_renew_interest"
                                                                               class="form-control"
                                                                               id="edit-refer-renew-interest{{ $key }}"
                                                                               value="{{ $properties['refer_renew_interest'][0]->value }}"
                                                                               placeholder="ইংরেজিতে লিখুন">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label for="edit-is-default{{ $key }}" class="col-3 col-form-label text-right">ডিফল্ট প্যাকেজ:</label>
                                                                <div class="col-9">
                                                                    <label class="checkbox mt-3">
                                                                        <input type="checkbox" id="edit-is-default{{ $key }}" name="is_default" value="1" @if($properties['is_default'][0]->value){{ 'checked' }}@endif>
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="edit-refer-target{{ $key }}" class="col-3 col-form-label text-right">টার্গেট:</label>
                                                                <div class="col-9">
                                                                    <input type="number"
                                                                           name="refer_target"
                                                                           class="form-control"
                                                                           id="edit-refer-target{{ $key }}"
                                                                           value="{{ $properties['refer_target'][0]->value }}"
                                                                           placeholder="ইংরেজিতে লিখুন">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row align-items-center">
                                                                <label for="edit-refer-fail-onetime-interest{{ $key }}" class="col-3 col-form-label text-right">ব্যর্থ হলে এককালীন ইন্টারেস্ট:</label>
                                                                <div class="col-9">
                                                                    <div class="input-group">
                                                                        <input type="number"
                                                                               name="refer_fail_onetime_interest"
                                                                               class="form-control"
                                                                               id="edit-refer-fail-onetime-interest{{ $key }}"
                                                                               value="{{ $properties['refer_fail_onetime_interest'][0]->value }}"
                                                                               placeholder="ইংরেজিতে লিখুন">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row align-items-center">
                                                                <label for="edit-refer-fail-renew-interest{{ $key }}" class="col-3 col-form-label text-right">ব্যর্থ হলে রিনিউ ইন্টারেস্ট:</label>
                                                                <div class="col-9">
                                                                    <div class="input-group">
                                                                        <input type="number"
                                                                               name="refer_fail_renew_interest"
                                                                               class="form-control"
                                                                               id="edit-refer-fail-renew-interest{{ $key }}"
                                                                               value="{{ $properties['refer_fail_renew_interest'][0]->value }}"
                                                                               placeholder="ইংরেজিতে লিখুন">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                <p class="modal-title h5" id="exampleModalLabel">সত্যিই কি আপনি এই ক্যাটাগরিটি মুছে ফেলতে চান?</p>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <form action="{{ route('backend.package.referrer.destroy', $package->id) }}" method="post">
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
                            <td colspan="6">কোনো প্যাকেজ নেই।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-12">
                        @include('components.side-nav')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="button" href="javascript:" class="btn btn-info w-100" data-toggle="modal" data-target="#createModal">প্যাকেজ তৈরি করুন</button>
                        <!-- Create Modal -->
                        <div class="modal fade" id="createModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">প্যাকেজ তৈরি করুন</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form data-action="{{ route('backend.package.referrer.store') }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="create-name" class="col-3 col-form-label text-right">নাম:</label>
                                                        <div class="col-9">
                                                            <input type="text" name="name" class="form-control" id="create-name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="create-description" class="col-3 col-form-label text-right">বর্ণনা:</label>
                                                        <div class="col-9">
                                                            <textarea name="description" id="create-description" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="create-duration" class="col-3 col-form-label text-right">সময়:</label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <input type="number"
                                                                       name="duration"
                                                                       class="form-control"
                                                                       id="create-duration"
                                                                       placeholder="ইংরেজিতে লিখুন">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">দিন</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="create-refer-onetime-interest" class="col-3 col-form-label text-right">এককালীন ইন্টারেস্ট:</label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <input type="number"
                                                                       name="refer_onetime_interest"
                                                                       class="form-control"
                                                                       id="create-refer-onetime-interest"
                                                                       placeholder="ইংরেজিতে লিখুন"
                                                                       required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="create-refer-renew-interest" class="col-3 col-form-label text-right">রিনিউ ইন্টারেস্ট:</label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <input type="number"
                                                                       name="refer_renew_interest"
                                                                       class="form-control"
                                                                       id="create-refer-renew-interest"
                                                                       placeholder="ইংরেজিতে লিখুন">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label for="create-refer-target" class="col-3 col-form-label text-right">ডিফল্ট প্যাকেজ:</label>
                                                        <div class="col-9">
                                                            <label for="create-is-default" class="checkbox mt-3">
                                                                <input type="checkbox" id="create-is-default" name="is_default" value="1">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="create-refer-target" class="col-3 col-form-label text-right">টার্গেট:</label>
                                                        <div class="col-9">
                                                            <input type="number"
                                                                   name="refer_target"
                                                                   class="form-control"
                                                                   id="create-refer-target"
                                                                   placeholder="ইংরেজিতে লিখুন">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="create-refer-fail-onetime-interest" class="col-3 col-form-label text-right">ব্যর্থ হলে এককালীন ইন্টারেস্ট:</label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <input type="number"
                                                                       name="refer_fail_onetime_interest"
                                                                       class="form-control"
                                                                       id="create-refer-fail-onetime-interest"
                                                                       placeholder="ইংরেজিতে লিখুন">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row align-items-center">
                                                        <label for="create-refer-fail-renew-interest" class="col-3 col-form-label text-right">ব্যর্থ হলে রিনিউ ইন্টারেস্ট:</label>
                                                        <div class="col-9">
                                                            <div class="input-group">
                                                                <input type="number"
                                                                       name="refer_fail_renew_interest"
                                                                       class="form-control"
                                                                       id="create-refer-fail-renew-interest"
                                                                       placeholder="ইংরেজিতে লিখুন">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
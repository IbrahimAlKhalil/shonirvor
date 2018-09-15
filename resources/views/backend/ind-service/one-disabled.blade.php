@extends('layouts.backend.master')

@section('title', $indService->user->name)

@section('content')
    <div class="container py-5">
        @include('components.success')
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <a href="{{ asset('storage/' . $indService->user->photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $indService->user->photo) }}" class="img-responsive img-thumbnail"
                                 alt="{{ $indService->user->name }}">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $indService->user->name }}</h4>
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <tbody>
                                <tr>
                                    <th scope="row">Mobile</th>
                                    <td>{{ $indService->mobile }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $indService->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Age</th>
                                    <td>{{ $indService->user->age }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Qualification/Experience</th>
                                    <td>{{ $indService->user->qualification }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">NID</th>
                                    <td>{{ $indService->user->nid }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address</th>
                                    <td>{{ $indService->address }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <button class="btn btn-secondary btn-success mx-auto" onclick="confirm('Are You Sure?') && document.getElementById('activate-account').submit()">Activate</button>
                            <form id="activate-account" action="{{ route('individual-service.activate') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $indService->id }}">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">Documents</h3>
                    @forelse($indService->docs as $document)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $document->doc) }}" target="_blank">
                                <img src="{{ asset('storage/' . $document->doc) }}" class="img-responsive img-thumbnail">
                            </a>
                        </div>
                    @empty
                        <p class="text-muted col-12">No Document Uploaded!</p>
                    @endforelse
                </div>

                <div class="row">
                    <h3 class="my-4 col-12">Images</h3>
                    @forelse($indService->images as $image)
                        <div class="col-md-3">
                            <a href="{{ asset('storage/' . $image->image) }}" target="_blank">
                                <img src="{{ asset('storage/' . $image->image) }}" class="img-responsive img-thumbnail">
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
                        <button type="button" href="javascript:" class="btn btn-info w-100" data-toggle="modal" data-target="#notificationModal">নোটিফিকেশন পাঠান</button>
                        <!-- Modal -->
                        <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">নোটিফিকেশন</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('notification.send', $indService->user->id) }}" method="post">
                                        {{ csrf_field() }}
                                        <div class="modal-body">
                                            <textarea name="notification" class="form-control" rows="4"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">বাতিল</button>
                                            <button type="submit" class="btn btn-primary">পাঠান</button>
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
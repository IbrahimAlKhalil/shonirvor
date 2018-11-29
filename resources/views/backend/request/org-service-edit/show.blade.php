@extends('layouts.backend.master')

@section('title', $application->serviceEditable->name)

@section('webpack')
    <script src="{{ asset('assets/js/backend/ind-service-request/show.bundle.js') }}"></script>
@endsection

@section('content')
    <style>
        .user-photo {
            width: 100px;
            height: 100px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
    <div class="container d-flex justify-content-center">
        <div class="bg-white mt-4 p-4 rounded row col-9">
            <div class="col-md-12 mb-3">
                <div class="rounded row">
                    <div class="col-md-12 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="rounded-circle shadow user-photo"
                                     style="background-image: url({{ asset('storage/' . $application->serviceEditable->logo) }});"></div>
                            </div>
                            <div class="col-md-9">
                                <div class="w-100 h-100 d-flex align-items-center">
                                    <a href="{{ route('backend.users.show', $application->service_editable_id) }}">{{ $application->serviceEditable->name }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0 list-group mt-4">
                        <table class="table-sm table-striped table-hover">
                            <tbody>
                            <tr>
                                <th scope="row">মোবাইলঃ</th>
                                <td>{{ $data['mobile']  }}</td>
                            </tr>
                            <tr>
                                <th scope="row">মোবাইলঃ</th>
                                <td>{{ $data['email']  }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ওয়েবসাইটঃ</th>
                                <td>{{ $data['website'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ফেসবুকঃ</th>
                                <td>{{ $data['facebook'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> বিভাগঃ</th>
                                <td>{{ $division->bn_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> জেলাঃ</th>
                                <td>{{ $district->bn_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> থানাঃ</th>
                                <td>{{ $thana->bn_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> ইউনিয়নঃ</th>
                                <td>{{ $union->bn_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> এলাকাঃ</th>
                                <td>{{ $village->bn_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row"> ঠিকানাঃ</th>
                                <td>{{ $data['address'] }}</td>
                            </tr>
                            @isset($data['logo'])
                                <tr>
                                    <th scope="row"> লোগোঃ</th>
                                    <td>
                                        <a href="{{ asset('storage/' . $data['logo']) }}"
                                           target="_blank">
                                            <img src="{{ asset('storage/' . $data['logo']) }}"
                                                 style="height: 50px;"
                                                 class="img-fluid img-thumbnail">
                                        </a>
                                    </td>
                                </tr>
                            @endisset
                            @isset($data['cover-photo'])
                                <tr>
                                    <th scope="row"> কভার ছবিঃ</th>
                                    <td>
                                        <a href="{{ asset('storage/' . $data['cover-photo']) }}"
                                           target="_blank">
                                            <img src="{{ asset('storage/' . $data['cover-photo']) }}"
                                                 style="height: 50px;"
                                                 class="img-fluid img-thumbnail">
                                        </a>
                                    </td>
                                </tr>
                            @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($subCategories || isset($data['sub-category-requests']))
                <div class="col-md-12 mb-3">
                    <div class="rounded row">
                        <div class="col-md-12 p-0 list-group mt-4">
                            <table class="table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">সাব-ক্যাটাগরি</th>
                                    <th scope="col">মূল্য</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($subCategories as $index => $subCategory)
                                    <tr>
                                        <td> {{ en2bnNumber($index+1) }} </td>
                                        <td>{{ $subCategory['name'] }}</td>
                                        <td>{{ $subCategory['rate'] }}</td>
                                    </tr>
                                @endforeach
                                @isset($data['sub-category-requests'])
                                    @foreach($data['sub-category-requests'] as $index => $subCategory)
                                        <tr>
                                            <td> {{ en2bnNumber($index+1) }} </td>
                                            <td>
                                                <input type="text" class="form-control"
                                                       form="approve-form"
                                                       name="sub-category-requests[{{ $index }}][name]"
                                                       value="{{ $subCategory['name'] }}">
                                            </td>
                                            <td>{{ $subCategory['rate'] }}
                                                <input type="hidden" class="form-control"
                                                       form="approve-form"
                                                       name="sub-category-requests[{{ $index }}][rate]"
                                                       value="{{ $subCategory['rate'] }}"></td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @isset($data['kaj'])
                <div class="col-md-12 mb-3">
                    <div class="rounded row">
                        <div class="col-md-12 p-0 list-group mt-4">
                            <table class="table-sm table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">নামঃ</th>
                                    <th scope="col">তথ্য</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['kaj'] as $index => $kaj)
                                    <tr>
                                        <td>
                                            {{ $kaj['name'] }}
                                        </td>
                                        <td>
                                            {{ $kaj['info'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                @isset($data['kaj-requests'])
                                    @foreach($data['kaj-requests'] as $index => $kaj)
                                        <tr>
                                            <td>
                                                {{ $kaj['name'] }}
                                            </td>
                                            <td>
                                                {{ $kaj['info'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endisset

            @if((isset($data['images']) && count($data['images'])) || (isset($data['new-work-images']) && count($data['new-work-images'])))
                <div class="col-md-12 mb-3">
                    <div class="rounded row">
                        <div class="col-md-12 p-0 list-group mt-4">
                            <table class="table-sm table-striped table-hover table-responsive">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">কাজের ছবি</th>
                                    <th scope="col">বর্ণনা</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($count = 0)
                                @foreach($data['images'] as $id => $image)
                                    <tr>
                                        <td>
                                            {{ en2bnNumber(++$count) }}
                                        </td>
                                        <td>
                                            @if(isset($image['file']))
                                                <a href="{{ asset('storage/' . $image['file']) }}">
                                                    <img src="{{ asset('storage/' . $image['file']) }}"
                                                         style="max-width: 150px; min-width: 150px;"
                                                         class="img-fluid img-thumbnail">
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/' . $workImages->firstWhere('id', $id)->path) }}">
                                                    <img src="{{ asset('storage/' . $workImages->firstWhere('id', $id)->path) }}"
                                                         style="max-width: 150px; min-width: 150px;"
                                                         class="img-fluid img-thumbnail">
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $image['description'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                @isset($data['new-work-images'])
                                    @foreach($data['new-work-images'] as $image)
                                        <tr>
                                            <td>
                                                {{ en2bnNumber(++$count) }}
                                            </td>
                                            <td>
                                                <a href="{{ asset('storage/' . $image['file']) }}">
                                                    <img src="{{ asset('storage/' . $image['file']) }}"
                                                         style="max-width: 150px; min-width: 150px;"
                                                         class="img-fluid img-thumbnail">
                                                </a>
                                            </td>
                                            <td>
                                                {{ $image['description'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-12">
                <div class="p-2 rounded row d-flex justify-content-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#acceptModal">
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

    <form action="{{ route('backend.request.org-service-edit.store') }}" id="approve-form"
          method="post">
        <input type="hidden" value="{{ $application->id }}" name="application-id">
        {{ csrf_field() }}
    </form>

    <form action="{{ route('backend.request.org-service-edit.destroy', $application->id) }}" id="delete-form"
          method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection
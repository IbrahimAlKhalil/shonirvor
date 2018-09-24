 @extends('layouts.backend.master')

@section('title', 'ব্যক্তি সেবা প্রদানকারী ক্যাটাগরি: ' . $category->name)

 @section('webpack')
     <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
 @endsection

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-md-12 mt-4">
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endforeach
                @include('components.success')
                <div class="card">
                    <h5 class="card-header">ক্যাটাগরি: {{ $category->name }}
                        <div class="btn-group pull-right">
                            <a href="#"
                               class="btn btn-secondary"
                               onclick="document.getElementById('edit-category').value = prompt('ক্যাটাগরির নাম:', '{{ $category->name }}'); document.getElementById('update-form').submit(); return false">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <a href="#" onclick="document.getElementById('delete-form').submit(); return false"
                               class="btn btn-secondary btn-danger rounded-right"><i
                                        class="fa fa-trash-o"></i></a>
                            <form id="update-form" action="{{ route('individual-category.update', $category->id) }}"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input id="edit-category" name="category" type="hidden"
                                       value="{{ $category->name }}">
                            </form>
                            <form id="delete-form" action="{{ route('individual-category.destroy', $category->id) }}"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                            </form>
                        </div>
                    </h5>
                </div>
            </div>
            <div class="col-12 mt-4">
                <h4 class="mb-4">সাব-ক্যাটাগরি</h4>
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">সাব-ক্যাটাগরি</th>
                        <th scope="col">পদক্ষেপ</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($subCategories as $subCategory)
                        @php($serial = $subCategories->perPage() * ($subCategories->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ en2bnNumber($serial) }}</td>
                            <td>{{ $subCategory->name }}</td>
                            <td>
                                <div class="btn-group mx-auto">
                                    <a href="#" class="mr-4"
                                       onclick="document.getElementById('edit-category-{{ $subCategory->id }}').value = prompt('সাব-ক্যাটাগরি:', '{{ $subCategory->name }}'); document.getElementById('update-form-{{ $subCategory->id }}').submit(); return false"><i
                                                class="fa fa-pencil-square-o"></i></a>
                                    <a href="#"
                                       onclick="document.getElementById('delete-form-{{ $subCategory->id }}').submit(); return false">
                                        <i class="fa fa-trash-o"></i>
                                    </a>

                                    <form id="update-form-{{ $subCategory->id }}"
                                          action="{{ route('individual-sub-category.update', $subCategory->id) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <input id="edit-category-{{ $subCategory->id }}" type="hidden"
                                               value="{{ $subCategory->name }}" name="edit-sub-category">
                                    </form>
                                    <form id="delete-form-{{ $subCategory->id }}"
                                          action="{{ route('individual-sub-category.destroy', $subCategory->id) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">কোনো ক্যাটাগরি খুঁজে পাওয়া যায়নি ।</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $subCategories->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))
                @include('components.create-ind-sub-category', ['id' => $category->id])
            </div>
        </div>
    </div>
@endsection
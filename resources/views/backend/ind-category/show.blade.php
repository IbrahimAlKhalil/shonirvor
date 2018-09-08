@extends('layouts.backend.master')

@section('title', 'Individual Category: ' . $indCategory->category)

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
                    <h5 class="card-header">Category: {{ $indCategory->category }}
                        <div class="btn-group pull-right">
                            <a href="#"
                               class="btn btn-secondary"
                               onclick="document.getElementById('edit-category').value = prompt('Category Name:', '{{ $indCategory->category }}'); document.getElementById('update-form').submit(); return false">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <a href="#" onclick="document.getElementById('delete-form').submit(); return false"
                               class="btn btn-secondary btn-danger"><i
                                        class="fa fa-trash-o"></i></a>
                            <form id="update-form" action="{{ route('individual-category.update', $indCategory->id) }}"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input id="edit-category" name="category" type="hidden"
                                       value="{{ $indCategory->category }}">
                            </form>
                            <form id="delete-form" action="{{ route('individual-category.destroy', $indCategory->id) }}"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                            </form>
                        </div>
                    </h5>
                </div>
            </div>
            <div class="col-12 mt-4">
                <h4 class="mb-4">Sub-categories</h4>
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($indSubCategories as $indSubCategory)
                        @php($serial = $indSubCategories->perPage() * ($indSubCategories->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>{{ $indSubCategory->category }}</td>
                            <td>
                                <div class="btn-group mx-auto">
                                    <a href="#" class="mr-4"
                                       onclick="document.getElementById('edit-category-{{ $indSubCategory->id }}').value = prompt('Sub-Category Name:', '{{ $indSubCategory->category }}'); document.getElementById('update-form-{{ $indSubCategory->id }}').submit(); return false"><i
                                                class="fa fa-pencil-square-o"></i></a>
                                    <a href="#"
                                       onclick="document.getElementById('delete-form-{{ $indSubCategory->id }}').submit(); return false">
                                        <i class="fa fa-trash-o"></i>
                                    </a>

                                    <form id="update-form-{{ $indSubCategory->id }}"
                                          action="{{ route('individual-sub-category.update', $indSubCategory->id) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('put') }}
                                        <input id="edit-category-{{ $indSubCategory->id }}" type="hidden"
                                               value="{{ $indSubCategory->category }}" name="edit-sub-category">
                                    </form>
                                    <form id="delete-form-{{ $indSubCategory->id }}"
                                          action="{{ route('individual-sub-category.destroy', $indSubCategory->id) }}"
                                          method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No Category Found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $indSubCategories->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))

                <div class="card mt-4">
                    <h5 class="card-header">Add New Sub-category</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('individual-sub-category.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="category-id" value="{{ $indCategory->id }}">
                            <label for="category" class="label">Category Name</label>
                            <input id="category"
                                   class="form-control @if($errors->has('sub-category')){{ 'is-invalid' }}@endif"
                                   type="text" name="sub-category">
                            @include('components.invalid', ['name' => 'sub-category'])
                            <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
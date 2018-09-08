@extends('layouts.backend.master')

@section('title', 'All Individual Service Categories')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="btn-group mx-auto">
                <a href="{{ route('individual-category.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('individual-category.index')){{ 'active' }}@endif">Individual</a>
                <a href="{{ route('organization-category.index') }}"
                   class="btn btn-secondary @if(request()->url() == route('organization-category.index')){{ 'active' }}@endif">Organization</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-4">
                <h4 class="mb-4">All Individual Service Categories</h4>
                @include('components.success')
            </div>
            <div class="col-md-9">
                <table class="table table-striped table-bordered table-hover table-sm text-center">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Category</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orgCategories as $orgCategory)
                        @php($serial = $orgCategories->perPage() * ($orgCategories->currentPage() - 1) + $loop->iteration)
                        <tr>
                            <td>{{ $serial }}</td>
                            <td>
                                <a href="{{ route('organization-category.show', $orgCategory->id) }}">{{ $orgCategory->category }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No Service Category Found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="row">
                    <div class="mx-auto">
                        {{ $orgCategories->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @include('components.side-nav', compact('navs'))

                <div class="card mt-4">
                    <h5 class="card-header">Add New Category</h5>
                    <div class="card-body">
                        <form method="post" action="{{ route('organization-category.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="category-id" value="{{ $orgCategory->id }}">
                            <label for="category" class="label">Category Name</label>
                            <input id="category"
                                   class="form-control @if($errors->has('category')){{ 'is-invalid' }}@endif"
                                   type="text" name="category">
                            @include('components.invalid', ['name' => 'category'])
                            <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
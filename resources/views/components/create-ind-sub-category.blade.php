<div class="card mt-4">
    <h5 class="card-header">Add New Sub-category</h5>
    <div class="card-body">
        <form method="post" action="{{ route('individual-sub-category.store') }}">
            {{ csrf_field() }}
            <input type="hidden" name="category-id" value="{{ $id }}">
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
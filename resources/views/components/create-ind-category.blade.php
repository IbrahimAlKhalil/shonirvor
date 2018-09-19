<div class="card mt-4">
    <h5 class="card-header">নতুন বিভাগ জুড়ুন</h5>
    <div class="card-body">
        <form method="post" action="{{ route('individual-category.store') }}">
            {{ csrf_field() }}
            <label for="category" class="label">বিভাগের নাম</label>
            <input id="category"
                   class="form-control @if($errors->has('category')){{ 'is-invalid' }}@endif"
                   type="text" name="category">
            @include('components.invalid', ['name' => 'category'])
            <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">সাবমিট</button>
        </form>
    </div>
</div>
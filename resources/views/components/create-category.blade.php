<div class="card mt-4">
    <h5 class="card-header">নতুন ক্যাটাগরি জুড়ুন</h5>
    <div class="card-body">
        <form method="post" action="{{ $action }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label for="category" class="label">ক্যাটাগরিের নাম</label>
            <input id="category"
                   class="form-control @if($errors->has('category')){{ 'is-invalid' }}@endif"
                   type="text" name="category">
            @include('components.invalid', ['name' => 'category'])
            <input type="file" accept="image/*" name="image" class="form-control mt-2" required>
            <button class="mt-3 btn btn-secondary btn-success rounded pull-right" type="submit">সাবমিট</button>
        </form>
    </div>
</div>
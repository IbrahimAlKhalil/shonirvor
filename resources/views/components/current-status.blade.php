<div class="card">
    <h5 class="card-header">বর্তমান স্ট্যাটাস</h5>
    <div class="card-body">
        <div class="form-group">
            <form action="{{ $action }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $id }}" name="id">
                <p>স্ট্যাটাস</p>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success {{ isActive($isAvailable) }}">
                        <input type="radio" name="is-available" value="yes" id="active"
                               autocomplete="off" {{ checkBox($isAvailable) }}>
                        একটিভ
                    </label>
                    <label class="btn btn-danger {{ isActive(!$isAvailable) }}">
                        <input type="radio" name="is-available" value="no" id="busy"
                               autocomplete="off" {{ checkBox(!$isAvailable) }}> ব্যাস্ত
                    </label>
                </div>
                <div class="form-group mt-2">
                    <label for="status">ম্যাসেজ</label>
                    <textarea type="text" id="status" class="form-control" name="message">{{ $message }}</textarea>
                </div>
                <button class="btn btn-primary" type="submit">আপডেট</button>
            </form>
        </div>
    </div>
</div>
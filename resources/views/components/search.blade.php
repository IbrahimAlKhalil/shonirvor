<form action="{{ route('frontend.filter') }}">
    <div class="input-group">
        <input type="text" name="village" id="searchVillageInput" class="form-control" placeholder="এলাকা সার্চ করুন">
        <input type="text" name="sub-category" id="searchCategoryInput" value="{{ request()->get('flexdatalist-sub-category') }}" class="form-control" placeholder="সার্ভিস সার্চ করুন">
        <div class="input-group-append">
            <button class="btn btn-outline-primary" type="submit">সার্চ</button>
        </div>
    </div>
</form>
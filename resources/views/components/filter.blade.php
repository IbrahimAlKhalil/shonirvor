<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header text-center">ফিল্টার করুন</div>
            <form action="{{ route('frontend.filter') }}">
                <div class="card-body">
                    <div class="form-group">
                        <label for="division">এলাকা</label>
                        <select name="division" id="division" class="form-control mb-3"
                                data-option-loader-url="{{ route('api.districts') }}"
                                data-target-select="#district"
                                data-option-loader-param="division">
                            <option value="">-- বিভাগ --</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                            @endforeach
                        </select>
                        <select name="district" id="district" class="form-control mb-3"
                                data-placeholder="-- জেলা --"
                                data-option-loader-url="{{ route('api.thanas') }}"
                                data-target-select="#thana"
                                data-option-loader-param="district"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- জেলা --</option>
                        </select>
                        <select name="thana" id="thana" class="form-control mb-3"
                                data-placeholder="-- থানা --"
                                data-option-loader-url="{{ route('api.unions') }}"
                                data-target-select="#union"
                                data-option-loader-param="thana"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- থানা --</option>
                        </select>
                        <select name="union" id="union" class="form-control mb-3"
                                data-placeholder="-- ইউনিয়ন --"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- ইউনিয়ন --</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">ক্যাটাগরি</label>
                        <select name="category" id="category" class="form-control mb-3"
                                data-option-loader-url="{{ route('api.sub-categories') }}"
                                data-target-select="#subCategory"
                                data-option-loader-param="category">
                            <option value="">-- ক্যাটাগরি --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="sub-category" id="subCategory" class="form-control"
                                data-placeholder="-- সাব ক্যাটাগরি --"
                                data-option-loader-properties="value=id,text=name">
                            <option value="">-- সাব ক্যাটাগরি --</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary">ফিল্টার</button>
                </div>
            </form>
        </div>
    </div>
</div>
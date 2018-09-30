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
                                data-option-loader-target="#district"
                                @if(request()->filled('division'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-param="division">
                            <option value="">-- বিভাগ --</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" @if(request()->get('division') == $division->id){{ 'selected' }}@endif>{{ $division->bn_name }}</option>
                            @endforeach
                        </select>
                        <select name="district" id="district" class="form-control mb-3"
                                data-placeholder="-- জেলা --"
                                data-option-loader-url="{{ route('api.thanas') }}"
                                data-option-loader-target="#thana"
                                data-option-loader-param="district"
                                @if(request()->filled('district'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- জেলা --</option>
                            @isset($districts)
                                @foreach($districts as $district)
                                    <option value="{{ $district->id }}" @if(request()->get('district') == $district->id){{ 'selected' }}@endif>{{ $district->bn_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <select name="thana" id="thana" class="form-control mb-3"
                                data-placeholder="-- থানা --"
                                data-option-loader-url="{{ route('api.unions') }}"
                                data-option-loader-target="#union"
                                @if(request()->filled('thana'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-param="thana"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- থানা --</option>
                            @isset($thanas)
                                @foreach($thanas as $thana)
                                    <option value="{{ $thana->id }}" @if(request()->get('thana') == $thana->id){{ 'selected' }}@endif>{{ $thana->bn_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                        <select name="union" id="union" class="form-control mb-3"
                                data-placeholder="-- ইউনিয়ন --"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- ইউনিয়ন --</option>
                            @isset($unions)
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" @if(request()->get('union') == $union->id){{ 'selected' }}@endif>{{ $union->bn_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">ক্যাটাগরি</label>
                        <select name="category" id="category" class="form-control mb-3"
                                data-option-loader-url="{{ route('api.sub-categories') }}"
                                data-option-loader-target="#subCategory"
                                @if(request()->filled('category'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-param="category">
                            <option value="">-- ক্যাটাগরি --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if(request()->get('category') == $category->id){{ 'selected' }}@endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select name="sub-category" id="subCategory" class="form-control"
                                data-placeholder="-- সাব ক্যাটাগরি --"
                                data-option-loader-nodisable="true"
                                data-option-loader-properties="value=id,text=name">
                            <option value="">-- সাব ক্যাটাগরি --</option>
                            @isset($subCategories)
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" @if(request()->get('sub-category') == $subCategory->id){{ 'selected' }}@endif>{{ $subCategory->name }}</option>
                                @endforeach
                            @endisset
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
<div class="row justify-content-center mt-4">
    <div class="col-11 mx-5 pt-2">
        <div class="filter-root bg-white mr-3 rounded py-3">
            <form action="#">
                <div class="row">
                    <div class="col-2 my-auto pl-5">
                        <div class="row">
                            <div class="col-12">
                                <label class="radio-container">বেক্তিগত
                                    <input type="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-12">
                                <label class="radio-container">প্রাতিষ্ঠানিক
                                    <input type="radio" name="radio">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <select name="division" id="division" class="form-control"
                                        data-option-loader-url="{{ route('api.districts') }}"
                                        data-option-loader-target="#district"
                                        @if(request()->filled('division'))
                                        data-option-loader-nodisable="true"
                                        @endif
                                        data-option-loader-param="division">
                                    <option value="">-- বিভাগ --</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" @if(request()->get('division') == $division->id){{ 'selected' }}@endif>{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select name="district" id="district" class="form-control"
                                        data-placeholder="-- জেলা --"
                                        data-option-loader-url="{{ route('api.thanas') }}"
                                        data-option-loader-target="#thana"
                                        data-option-loader-param="district"
                                        @if(request()->filled('district'))
                                        data-option-loader-nodisable="true"
                                        @endif
                                        data-option-loader-properties="value=id,text=bn_name">
                                    @isset($districts)
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}" @if(request()->get('district') == $district->id){{ 'selected' }}@endif>{{ $district->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select name="thana" id="thana" class="form-control"
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
                                            <option value="{{ $thana->id }}" @if(request()->get('thana') == $thana->id){{ 'selected' }}@endif>{{ $thana->name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select name="union" id="union" class="form-control"
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
                            <div class="col-md-3">
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
                            </div>
                            <div class="col-md-3">
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
                            <div class="col-md-3 my-auto">
                                <label class="radio-container">দাম কম থেকে বেশি
                                    <input type="radio" name="radio2">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-3 my-auto">
                                <label class="radio-container">দাম বেশি থেকে কম
                                    <input type="radio" name="radio2">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 my-auto">
                        <button type="button" class="btn btn-info">সার্ভিস সার্চ করুন</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
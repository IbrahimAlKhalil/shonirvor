<div class="filter-root bg-white rounded py-3">
    <form action="{{ route('frontend.filter') }}">
        <div class="d-flex">
            <div class="px-4">
                <div class="pt-3 mb-0">
                    <label class="radio-container">বেক্তিগত
                        <input type="radio" name="type" value="ind" @if(request()->get('type') == 'ind'){{ 'checked' }}@endif>
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="pt-3 mb-0">
                    <label class="radio-container">প্রাতিষ্ঠানিক
                        <input type="radio" name="type" value="org" @if(request()->get('type') == 'org'){{ 'checked' }}@endif>
                        <span class="checkmark"></span>
                    </label>
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex">
                    <div class="flex-fill pr-1 py-2">
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
                    <div class="flex-fill px-1 py-2">
                        <select name="district" id="district" class="form-control"
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
                                    <option value="{{ $district->id }}" @if(request()->get('district') == $district->id){{ 'selected' }}@endif>{{ $district->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="flex-fill px-1 py-2">
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
                    <div class="flex-fill px-1 py-2">
                        <select name="union" id="union" class="form-control"
                                data-placeholder="-- ইউনিয়ন --"
                                data-option-loader-url="{{ route('api.villages') }}"
                                data-option-loader-target="#village"
                                @if(request()->filled('union'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-param="union"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- ইউনিয়ন --</option>
                            @isset($unions)
                                @foreach($unions as $union)
                                    <option value="{{ $union->id }}" @if(request()->get('union') == $union->id){{ 'selected' }}@endif>{{ $union->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="flex-fill pl-1 py-2">
                        <select name="village" id="village" class="form-control"
                                data-placeholder="-- এলাকা --"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- এলাকা --</option>
                            @isset($villages)
                                @foreach($villages as $village)
                                    <option value="{{ $village->id }}" @if(request()->get('village') == $village->id){{ 'selected' }}@endif>{{ $village->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="col-3 py-2 pl-0 pr-1">
                        <select name="category" id="category" class="form-control"
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
                    <div class="col-3 py-2 px-1">
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
                    <div class="col-3 pt-3 pb-2 pl-4 pr-0">
                        <label class="radio-container">দাম কম থেকে বেশি
                            <input type="radio" name="price" value="low" @if(request()->get('price') == 'low'){{ 'checked' }}@endif>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <div class="col-3 pt-3 pb-2 pl-4 pr-0">
                        <label class="radio-container">দাম বেশি থেকে কম
                            <input type="radio" name="price" value="high" @if(request()->get('price') == 'high'){{ 'checked' }}@endif>
                            <span class="checkmark"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="m-auto px-4">
                <button type="submit" class="btn btn-info">সার্ভিস সার্চ করুন</button>
            </div>
        </div>
    </form>
</div>
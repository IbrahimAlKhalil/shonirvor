<div class="filter-root bg-white rounded py-3">
    <form action="{{ route('frontend.filter') }}">
        <div class="d-md-flex">
            <div class="flex-grow-1">
                <div class="d-md-flex px-4 px-md-0">
                    <div class="flex-fill pl-md-4 pr-md-1 py-2">
                        <select name="type" id="service-type"
                                data-option-loader-url="{{ route('api.categories') }}"
                                data-option-loader-target="#category"
                                data-option-loader-param="type">
                            <option value="">-- সার্ভিসের ধরন --</option>
                            <option value="ind" @if(request()->get('type') == 'ind'){{ 'selected' }}@endif>
                                বেক্তিগত
                            </option>
                            <option value="org" @if(request()->get('type') == 'org'){{ 'selected' }}@endif>
                                প্রাতিষ্ঠানিক
                            </option>
                        </select>
                    </div>
                    <div class="flex-fill pr-md-1 py-2">
                        <select name="division" id="division"
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
                    <div class="flex-fill px-md-1 py-2">
                        <select name="district" id="district"
                                @if(! request()->filled('division')){{ 'disabled' }}@endif
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
                    <div class="flex-fill px-md-1 py-2">
                        <select name="thana" id="thana"
                                @if(! request()->filled('district')){{ 'disabled' }}@endif
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
                    <div class="flex-fill px-md-1 py-2">
                        <select name="union" id="union"
                                @if(! request()->filled('thana')){{ 'disabled' }}@endif
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
                    <div class="flex-fill pl-md-1 py-2">
                        <select name="village" id="village"
                                @if(! request()->filled('union')){{ 'disabled' }}@endif
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
                <div class="d-md-flex px-4 px-md-0">
                    <div class="col-md-3 py-2 pl-0 pr-0 pr-md-1">
                        <select name="category" id="category"
                                class="pl-md-4"
                                data-option-loader-url="{{ route('api.sub-categories') }}"
                                data-option-loader-target="#subCategory"
                                @if(request()->filled('category'))
                                data-option-loader-nodisable="true"
                                @endif
                                data-option-loader-param="category"
                                data-option-loader-properties="value=id,text=bn_name">
                            <option value="">-- ক্যাটাগরি --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if(request()->get('category') == $category->id){{ 'selected' }}@endif>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 py-2 px-0 px-md-1">
                        <select name="sub-category" id="subCategory"
                                @if(! request()->filled('category')){{ 'disabled' }}@endif
                                data-placeholder="-- সার্ভিস --"
                                data-option-loader-nodisable="true"
                                data-option-loader-properties="value=id,text=name">
                            <option value="">-- সার্ভিস --</option>
                            @isset($subCategories)
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" @if(request()->get('sub-category') == $subCategory->id){{ 'selected' }}@endif>{{ $subCategory->name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="col-md-3 py-2 pl-0 pr-0 pr-md-1">
                        <select name="method" id="method">
                            <option value="">-- Work Methods --</option>
                            @foreach($workMethods as $workMethod)
                                <option value="{{ $workMethod->id }}" @if(request()->get('method') == $workMethod->id){{ 'selected' }}@endif>{{ $workMethod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 py-2 pl-0 pr-0 pr-md-1">
                        <select name="price" id="price"
                                data-placeholder="-- দাম --">
                            <option value="">-- দাম --</option>
                            <option value="low" @if(request()->get('price') == 'low'){{ 'selected' }}@endif>
                                দাম কম থেকে বেশি
                            </option>
                            <option value="high" @if(request()->get('price') == 'high'){{ 'selected' }}@endif>
                                দাম বেশি থেকে কম
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="m-auto px-4 text-center text-md-left">
                <button type="submit" class="btn btn-info">সার্ভিস সার্চ করুন</button>
            </div>
        </div>
    </form>
</div>
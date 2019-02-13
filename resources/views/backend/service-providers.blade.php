@extends('layouts.backend.master')

@section('title', 'সকল সার্ভিস প্রভাইডার')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container-fluid">
        <div class="row mt-4 mx-auto" id="filters">
            <div class="card col">
                <div class="card-body">
                    <form class="row" action="{{ route('service-filter-data') }}" id="filter-form" method="get">
                        @csrf
                        <div class="col-md-2">
                            <select name="type" id="service-type"
                                    data-option-loader-url="{{ route('api.categories') }}"
                                    data-option-loader-target="#category"
                                    data-option-loader-param="type">
                                <option value="">-- Service Type --</option>
                                <option value="ind" @if(request()->get('type') == 'ind'){{ 'selected' }}@endif>
                                    Individual
                                </option>
                                <option value="org" @if(request()->get('type') == 'org'){{ 'selected' }}@endif>
                                    Organizational
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" id="category"
                                    data-option-loader-url="{{ route('api.sub-categories') }}"
                                    data-option-loader-target="#subCategory"
                                    @if(request()->filled('category'))
                                    data-option-loader-nodisable="true"
                                    @endif
                                    data-option-loader-param="category"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- Category --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sub-category" id="subCategory"
                                    @if(! request()->filled('category')){{ 'disabled' }}@endif
                                    data-placeholder="-- Sub Category --"
                                    data-option-loader-nodisable="true"
                                    data-option-loader-properties="value=id,text=name">
                                <option value="">-- Sub Category --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="division" id="division"
                                    data-option-loader-url="{{ route('api.districts') }}"
                                    data-option-loader-target="#district"
                                    data-option-loader-param="division">
                                <option value="">-- Division --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->bn_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="district" id="district"
                                    data-placeholder="-- District --"
                                    data-option-loader-url="{{ route('api.thanas') }}"
                                    data-option-loader-target="#thana"
                                    data-option-loader-param="district"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- District --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="thana" id="thana"
                                    data-placeholder="-- Thana --"
                                    data-option-loader-url="{{ route('api.unions') }}"
                                    data-option-loader-target="#union"
                                    data-option-loader-param="thana"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- Thana --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="union" id="union"
                                    data-placeholder="-- Union/Word --"
                                    data-option-loader-url="{{ route('api.villages') }}"
                                    data-option-loader-target="#village"
                                    data-option-loader-param="union"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- Union/Word --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="village" id="village"
                                    data-placeholder="-- Union/Word --"
                                    data-option-loader-properties="value=id,text=bn_name">
                                <option value="">-- Union/Word --</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="input-group" for="per-page">
                                <span class="input-group-prepend input-group-text">Show Per Page</span>
                                <input class="form-control col" type="number" value="10" name="per-page"
                                       id="per-page">
                            </label>

                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                                                             aria-hidden="true"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="service-providers">

            <div class="container-fluid my-3">
                <div class="row">
                    <div class="card col-md-4">
                        <form class="card-body">
                            <h3 class="card-title text-center"><label for="message">Send SMS</label></h3>
                            <textarea id="message" name="message" cols="30" rows="10" class="form-control"></textarea>
                            <div class="text-center mt-2">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="my-2 row">
            <span class="col-md-12">
                @{{ 'Total Result ' + pagination.total }}
            </span>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-white mb-2">
                        <form class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <select id="ind-package" class="form-control col-md-12"
                                            v-model="filters.indPackages.selected">
                                        <option v-for="package in filters.indPackages.data" :value="package.id">@{{
                                            package.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select id="org-package" class="form-control col-md-12"
                                            v-model="filters.orgPackages.selected">
                                        <option v-for="package in filters.orgPackages.data" :value="package.id">@{{
                                            package.name }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select id="top-or-normal" class="form-control col-md-12"
                                            v-model="filters.topOrNormal.selected">
                                        <option v-for="(text, value) in filters.topOrNormal.data" :value="value">
                                            @{{ text }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select id="status" class="form-control col-md-12"
                                            v-model="filters.statuses.selected">
                                        <option v-for="(text, value) in filters.statuses.data" :value="value">
                                            @{{ text }}
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select id="approved" class="form-control col-md-12"
                                            v-model="filters.pendingOrApproved.selected">
                                        <option v-for="(text, value) in filters.pendingOrApproved.data" :value="value">
                                            @{{ text }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <label class="input-group" for="start">
                                        <span class="input-group-prepend input-group-text">Expiry</span>
                                        <input class="form-control col" type="date" id="start"
                                               v-model="filters.expiry.start">
                                        <input class="form-control col" type="date" id="end"
                                               v-model="filters.expiry.end">
                                    </label>
                                </div>

                                <div class="col-md-1">
                                    <button @click="filter" class="btn btn-primary">
                                        <i class="fa fa-filter" aria-hidden="true"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-1">
                                    <button type="reset" class="btn btn-primary">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-sm text-center bg-white table-responsive-md">
                        <thead>
                        <tr>
                            <th scope="col"><a href="#" @click.prevent="checkAll">@</a></th>
                            <th scope="col">#</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Service Expiry</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Top Service</th>
                            <th scope="col">Service Package</th>
                            <th scope="col">Service Type</th>
                            <th scope="col">Approved</th>
                            <th scope="col">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-if="filtered.length">
                            <tr v-for="(service, index) in filtered" :key="service.id+service.type">
                                <td><input type="checkbox" v-model="service.checked"></td>
                                <td>@{{ ++index }}</td>
                                <td>
                                    <img class="service-photo"
                                         :src="getImage(service.photo)"
                                         :alt="service.name">
                                </td>
                                <td><a target="_blank" :href="getServiceUrl(service.id, service.type)">@{{ service.name
                                        }}</a></td>
                                <td>@{{ service.category_name }}</td>
                                <td>@{{ service.expire?service.expire:'n/a' }}</td>
                                <td>@{{ service.mobile }}</td>
                                <td>
                                    <span class="badge badge-success" v-if="service.top_expire">Yes</span>
                                    <span class="badge badge-danger" v-else>No</span>
                                </td>
                                <td>
                                    @{{ getPackageName(service) }}
                                </td>
                                <td>
                                    <span class="badge badge-primary" v-if="service.type==='ind'">Ind</span>
                                    <span class="badge badge-info" v-else>Org</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger" v-if="!service.expire">No</span>
                                    <span class="badge badge-success" v-else>Yes</span>
                                </td>
                                <td>
                                    <span class="badge badge-danger" v-if="service.deleted_at">Disabled</span>
                                    <span class="badge badge-success" v-else>Enabled</span>
                                </td>
                            </tr>
                        </template>
                        <template v-else-if="!loading && !pagination.data.length">
                            <tr>
                                <td colspan="10" class="text-center">কোন সেবা প্রদানকারী পাওয়া যায় নি।</td>
                            </tr>
                        </template>
                        <tr v-show="loading">
                            <td colspan="10" class="text-center">Loading...</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var saharaRoutes = {
            asset: '{{ asset('storage/') }}',
            individual: '{{ route('individual-service.show', '') }}',
            organization: '{{ route('organization-service.show', '') }}'
        };

        var saharaData = {
            indPackages: JSON.parse('{!! json_encode($indPackages) !!}'),
            orgPackages: JSON.parse('{!! json_encode($orgPackages) !!}')
        };
    </script>
    <script src="{{ asset('assets/js/backend/service-filter.bundle.js') }}"></script>
@endsection
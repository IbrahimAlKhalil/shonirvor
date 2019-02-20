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
                    <form class="row" action="{{ route('service-filter.get-data') }}" id="filter-form" method="get">
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
                            <button class="btn btn-secondary" type="submit"><i class="fa fa-search"
                                                                               aria-hidden="true"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="service-providers">

            <div class="card my-2">
                <div class="card-body">
                    <p class="text-muted">Please Wait, Sending Notification...</p>

                    <div class="progress">
                        <div class="progress-bar bg-secondary progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <div class="card my-2">
                <div class="card-body">
                    <p class="text-muted">Please Wait, Sending SMS...</p>

                    <div class="progress">
                        <div class="progress-bar bg-secondary progress-bar-animated"
                             style="width: 75%">3/100
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid my-3">
                <div class="row">
                    <div class="col-lg-4 my-2 pl-lg-0">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title text-center">
                                    <label for="message">Send SMS</label>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="mb-2">
                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" v-model="message.language" class="custom-control-input"
                                               name="lang" id="bn" value="bn">
                                        <label for="bn" class="custom-control-label">Bengali</label>
                                    </div>

                                    <div class="custom-control custom-radio d-inline-block">
                                        <input type="radio" v-model="message.language" class="custom-control-input"
                                               name="lang" id="en" value="en">
                                        <label for="en" class="custom-control-label">English</label>
                                    </div>
                                </div>

                                <div class="my-2">
                                    <button v-for="template in message.templates"
                                            class="btn btn-sm btn-outline-secondary m-1"
                                            @click="message.sms = template.message">@{{ template.name }}
                                    </button>
                                </div>

                                <textarea id="message" cols="30" rows="6"
                                          class="form-control" v-model="message.sms">
                                </textarea>

                                <p class="text-muted mt-1">
                                    <span class="float-left">
                                        @{{ message.sms.length }}/@{{ message.language === 'bn'?70:160 }}
                                    </span>
                                    <span class="float-right">
                                        SMS Count: @{{ Math.ceil(message.sms.length/(message.language === 'bn'?70:160)) }}
                                    </span>
                                </p>

                            </div>

                            <div class="card-footer text-center">
                                <button class="btn btn-secondary" type="button" @click.prevent="sendSms">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 my-2">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title text-center">
                                    <label for="notification">Send Notification</label>
                                </h3>
                            </div>

                            <div class="card-body">
                                <div class="my-2">
                                    <button v-for="template in message.templates"
                                            class="btn btn-sm btn-outline-secondary m-1"
                                            @click="message.notification = template.message">@{{ template.name }}
                                    </button>
                                </div>

                                <textarea id="notification" cols="30" rows="6"
                                          class="form-control" v-model="message.notification">
                                </textarea>
                            </div>

                            <div class="card-footer text-center">
                                <button class="btn btn-secondary" type="button" @click.prevent="sendNotification">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 my-2 pr-lg-0">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title text-center">
                                    <label for="notification">Templates</label>
                                </h3>
                            </div>

                            <div class="card-body p-0 message-templates">
                                <table class="table table-striped table-bordered table-hover table-sm text-center bg-white table-responsive-md">
                                    <thead>
                                    <tr>
                                        <th><a href="#" @click.prevent="checkAllTemplates">@</a></th>
                                        <th>#</th>
                                        <th>Name</th>
                                    </tr>
                                    </thead>

                                    <tbody is="transition-group" name="list">
                                    <tr v-if="!message.templates.length" key="no-template">
                                        <td colspan="4">No Templates Found</td>
                                    </tr>
                                    <tr v-else v-for="(template, index) in message.templates" :key="template.id">
                                        <td><input type="checkbox" v-model="template.checked"></td>
                                        <td>@{{ index+1 }}</td>
                                        <td><a href="#template-modal"
                                               @click="showMessage(template)"
                                               data-toggle="modal">@{{ template.name }}</a></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <div class="modal fade" id="template-modal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">
                                                    @{{ templateModalTitle }}
                                                </h6>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <span v-if="templateModal.type === 'show'">@{{ templateModal.message }}</span>
                                                <form v-else-if="templateModal.type === 'add'"
                                                      @submit.prevent="addTemplate" action=""
                                                      id="add-template-form">
                                                    <div class="form-group row">
                                                        <label for="template-name" class="col-md-4">Name</label>
                                                        <input type="text" name="name" class="form-control col-md-6"
                                                               id="template-name">
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="template-message" class="col-md-4">Message</label>
                                                        <textarea type="text" name="message"
                                                                  class="form-control col-md-6"
                                                                  id="template-message">
                                                        </textarea>
                                                    </div>

                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>
                                                <form v-else @submit.prevent="editTemplate" action=""
                                                      id="edit-template-form">
                                                    <input type="hidden" name="id" v-model="templateModal.template.id">
                                                    <div class="form-group row">
                                                        <label for="template-name" class="col-md-4">Name</label>
                                                        <input type="text" name="name" class="form-control col-md-6"
                                                               id="template-name" v-model="templateModal.template.name">
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="template-message" class="col-md-4">Message</label>
                                                        <textarea type="text" name="message"
                                                                  class="form-control col-md-6"
                                                                  id="template-message"
                                                                  v-model="templateModal.template.message">
                                                        </textarea>
                                                    </div>

                                                    <div class="text-center">
                                                        <button type="submit" class="btn btn-secondary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-center">
                                <div class="btn-group">
                                    <button class="btn btn-secondary btn-sm" type="button" @click="deleteTemplate">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    <button class="btn btn-secondary btn-sm" type="button"
                                            @click="showEditTemplateModal">
                                        <i class="fa fa-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-secondary btn-sm"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#template-modal"
                                            @click="showAddTemplateModal">
                                        <i class="fa fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
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
                        <form class="card-body" @submit.prevent="filter">
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
                                <div class="col-md-3">
                                    <select id="expired" class="form-control col-md-12"
                                            v-model="filters.expiry.selected">
                                        <option v-for="(text, value) in filters.expiry.data" :value="value">
                                            @{{ text }}
                                        </option>
                                    </select>
                                </div>

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
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-filter"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-1">
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="fa fa-trash"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-striped table-bordered table-hover table-sm text-center bg-white table-responsive-md">
                        <thead>
                        <tr>
                            <th scope="col"><a href="#" @click.prevent="checkAllServices">@</a></th>
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
                        <tbody is="transition-group" name="list">
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
                            <tr key="no-service">
                                <td colspan="10" class="text-center">কোন সেবা প্রদানকারী পাওয়া যায় নি।</td>
                            </tr>
                        </template>
                        <tr v-show="loading" key="loading">
                            <td colspan="10" class="text-center">Loading...</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row" v-if="pagination.per_page < pagination.total">
                <nav class="mx-auto">
                    <ul class="pagination" is="transition-group" name="list">
                        <li class="page-item" v-if="pagination.current_page > 1" key="previous">
                            <button class="page-link" @click="changePage(pagination.current_page - 1)">Previous</button>
                        </li>
                        <li :class="'page-item' + (pagination.current_page == page?' active':'')"
                            v-for="page in paginated" :key="'p'+page">
                            <button name="page" class="page-link" @click="changePage(page)">
                                @{{ page }}
                            </button>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page" key="next">
                            <button class="page-link" @click="changePage(pagination.current_page + 1)">Next</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var saharaRoutes = {
            asset: '{{ asset('storage/') }}',
            individual: '{{ route('individual-service.show', '') }}',
            organization: '{{ route('organization-service.show', '') }}',
            sendSms: '{{ route('service-filter.send-sms') }}',
            sendNotification: '{{ route('service-filter.send-notification') }}',
            messageTemplate: {
                store: '{{ route('message-templates.store') }}',
                destroy: '{{ route('message-templates.destroy') }}'
            }
        };

        var saharaData = {
            indPackages: JSON.parse('{!! json_encode($indPackages) !!}'),
            orgPackages: JSON.parse('{!! json_encode($orgPackages) !!}'),
            csrf: '{{ csrf_token() }}',
            messageTemplates: JSON.parse('{!! json_encode($smsTemplates) !!}').map(function (template) {
                template.checked = false;
                return template;
            }),
        };

        var today = new Date('{{ date('Y-m-d H:i:s') }}').getTime();
    </script>
    <script src="{{ asset('assets/js/backend/service-filter.bundle.js') }}"></script>
@endsection
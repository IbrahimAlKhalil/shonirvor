@extends('layouts.backend.master')

@section('title', 'Users')

@section('webpack')
    <script src="{{ asset('assets/js/backend/dashboard.bundle.js') }}"></script>
@endsection

@section('content')
    @include('components.success')
    <div class="container-fluid">
        <div class="row mt-4 mx-auto" id="filters">
            <div class="card col">
                <div class="card-body">
                    <form class="row" action="{{ route('backend.users.filter') }}" id="filter-form" method="get">
                        @csrf
                        <div class="col-md-2">
                            <label for="per-page">
                                <input class="form-control col" name="keyword" id="per-page">
                            </label>
                        </div>
                        <div class="col-md-2">
                            <label class="input-group" for="per-page">
                                <span class="input-group-prepend input-group-text">Per Page</span>
                                <input class="form-control col" type="number" value="10"
                                       name="per-page"
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

        <div id="root">

            <div is="transition-group" name="list">
                <div class="card my-2" v-for="(progress, index) in progresses" :key="'k' + index">
                    <div class="card-body">
                        <p class="text-muted">@{{ progress.message }}</p>

                        <div class="progress">
                            <div class="progress-bar bg-secondary progress-bar-animated"
                                 :style="{width: progress.done/(progress.total/100) + '%'}">@{{ progress.done }}/@{{
                                progress.total }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid my-3">
                <div class="row">
                    <div class="col-lg-6 my-2 pl-lg-0">
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
                                <button :disabled="sendingSms" class="btn btn-secondary" type="button"
                                        @click.prevent="sendSms">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 my-2 pr-lg-0">
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
                                        <th><a href="#" @click.prevent="checkAll(message.templates)">@</a></th>
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
                @{{ 'Total user ' + pagination.total }}
            </span>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered table-hover table-sm text-center bg-white table-responsive-md">
                        <thead>
                        <tr>
                            <th scope="col"><a href="#" @click.prevent="checkAll(filtered)">@</a></th>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Mobile</th>
                        </tr>
                        </thead>
                        <tbody is="transition-group" name="list">
                        <template v-if="filtered.length">
                            <tr v-for="(user, index) in filtered" :key="user.id">
                                <td><input type="checkbox" v-model="user.checked"></td>
                                <td>@{{ pagination.current_page * pagination.per_page - pagination.per_page+index+1 }}
                                </td>
                                <td>
                                    <a target="_blank"
                                       :href="routes.show+'/'+user.id">@{{ user.name }}</a>
                                </td>
                                <td>
                                    <img class="service-photo"
                                         :src="`${routes.asset}/${user.photo}`"
                                         :alt="user.name">
                                </td>

                                <td>@{{ user.mobile }}</td>
                            </tr>
                        </template>
                        <template v-else-if="!loading && !pagination.data.length">
                            <tr key="no-user">
                                <td colspan="10" class="text-center">Empty</td>
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
            show: '{{ route('backend.users.show', '') }}',
            sendSms: '{{ route('backend.users.send-sms') }}',
            messageTemplate: {
                store: '{{ route('message-templates.store') }}',
                destroy: '{{ route('message-templates.destroy') }}'
            }
        };

        var saharaData = {
            noImage: '{{ asset('storage/default/icons/no-image.svg') }}',
            csrf: '{{ csrf_token() }}',
            messageTemplates: JSON.parse('{!! json_encode($smsTemplates) !!}').map(function (template) {
                template.checked = false;
                return template;
            }),
        };

        var today = new Date('{{ date('Y-m-d H:i:s') }}').getTime();
    </script>
    <script src="{{ asset('assets/js/backend/user-filter.bundle.js') }}"></script>
@endsection

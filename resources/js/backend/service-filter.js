import '../modules/selectize-option-loader-plugin';
import '../../sass/backend/filter/index.scss';
import 'izitoast/dist/css/iziToast.min.css';
import iziToast from 'izitoast';
import Vue from "vue";
import Echo from 'laravel-echo';

$('#filters select').selectize({
    plugins: ['option-loader']
});

const form = document.getElementById('filter-form');

const vm = new Vue({
    el: '#service-providers',
    data: {
        loading: true,
        pagination: {
            data: []
        },
        filtered: [],
        routes: window.saharaRoutes,
        filters: {
            indPackages: {
                selected: 0,
                data: [{id: 0, name: '-- Ind Packages --'}, ...window.saharaData.indPackages]
            },

            orgPackages: {
                selected: 0,
                data: [{id: 0, name: '-- Org Packages --'}, ...window.saharaData.orgPackages]
            },

            topOrNormal: {
                selected: 'all',
                data: {
                    all: '-- Top Or Normal --',
                    normal: 'Normal',
                    top: 'Top Service'
                }
            },

            statuses: {
                selected: 'all',
                data: {
                    all: '-- Status --',
                    enabled: 'Enabled',
                    disabled: 'disabled'
                }
            },

            pendingOrApproved: {
                selected: 'all',
                data: {
                    all: '-- Pending Or Approved --',
                    pending: 'Pending',
                    approved: 'Approved'
                }
            },

            expiry: {
                start: '',
                end: '',
                within: 'none',
                selected: 'all',
                data: {
                    all: '-- Expired Or Non Expired --',
                    expired: 'Expired',
                    notExpired: 'Not Expired'
                }
            },

            paged: 1
        },
        message: {
            sms: '',
            notification: '',
            language: 'bn',
            templates: window.saharaData.messageTemplates
        },
        templateModal: {
            title: '',
            message: '',
            type: 'show',
            template: null
        }
    },
    methods: {
        getImage(image) {
            return `${this.routes.asset}/${image}`;
        },
        getServiceUrl(id, type) {
            return (type === 'ind' ? this.routes.individual : this.routes.organization) + '/' + id;
        },
        getPackageName(service) {
            if (!service.payments.length) {
                return 'n/a';
            }

            let payment;

            for (let p in service.payments) {
                payment = service.payments[p];
            }

            const properties = payment.package.properties;
            let name;

            properties.some(property => {
                if (property.name === 'name') {
                    name = property.value;
                    return true;
                }
            });

            return name;
        },
        filter() {
            const topOrNormal = this.filters.topOrNormal.selected;
            const status = this.filters.statuses.selected;
            const approved = this.filters.pendingOrApproved.selected;
            const indPackage = this.filters.indPackages.selected;
            const orgPackage = this.filters.orgPackages.selected;
            const expired = this.filters.expiry.selected;
            const startValue = this.filters.expiry.start;
            const endValue = this.filters.expiry.end;

            let filtered;

            switch (topOrNormal) {
                case 'normal':
                    filtered = this.pagination.data.filter(service => {
                        return !service.top_expire;
                    });
                    break;
                case 'top':
                    filtered = this.pagination.data.filter(service => {
                        return !!service.top_expire;
                    });
                    break;
                default:
                    filtered = this.pagination.data;
            }


            switch (status) {
                case 'enabled':
                    filtered = filtered.filter(service => {
                        return !service.deleted_at;
                    });
                    break;
                case 'disabled':
                    filtered = filtered.filter(service => {
                        return !!service.deleted_at;
                    });
                    break;
            }

            switch (approved) {
                case 'pending':
                    filtered = filtered.filter(service => {
                        return !service.expire;
                    });
                    break;
                case 'approved':
                    filtered = filtered.filter(service => {
                        return !!service.expire;
                    });
            }

            if (orgPackage !== 0) {
                filtered = filtered.filter(service => {
                    return (service.type === 'org' && service.payments && service.payments.length && service.payments[0].package_id === orgPackage) || service.type === 'ind';
                });
            }

            if (indPackage !== 0) {
                filtered = filtered.filter(service => {
                    return (service.type === 'ind' && service.payments && service.payments.length && service.payments[0].package_id === orgPackage) || service.type === 'org';
                });
            }

            switch (expired) {
                case 'expired':
                    filtered = filtered.filter(service => {
                        if (!service.expire) {
                            return false;
                        }

                        const expire = new Date(service.expire).getTime();

                        return expire < window.today;
                    });
                    break;
                case 'notExpired':
                    filtered = filtered.filter(service => {
                        if (!service.expire) {
                            return false;
                        }

                        const expire = new Date(service.expire).getTime();

                        return expire > window.today;
                    });
            }

            if (startValue && endValue) {
                filtered = filtered.filter(service => {
                    if (!service.expire) {
                        return false;
                    }

                    const expireTime = new Date(service.expire).getTime();
                    const startTime = new Date(startValue).getTime();
                    const endTime = new Date(endValue).getTime();

                    return expireTime > startTime && expireTime < endTime;
                });
            }

            this.filtered = filtered;
        },
        checkAllServices() {
            this.filtered.forEach(item => {
                item.checked = !item.checked;
            });
        },
        checkAllTemplates() {
            this.message.templates.forEach(item => {
                item.checked = !item.checked;
            });
        },
        changePage(paged) {
            fetchData(paged);
        },
        showMessage(template) {
            this.templateModal.type = 'show';
            this.templateModal.title = template.name;
            this.templateModal.message = template.message;
        },
        showAddTemplateModal() {
            this.templateModal.type = 'add';
        },
        addTemplate() {
            $('#template-modal').modal('hide');
            const formData = new FormData(document.getElementById('add-template-form'));
            formData.append('_token', window.saharaData.csrf);

            fetch(this.routes.messageTemplate.store, {
                method: 'POST',
                body: formData
            }).then(response => response.json().then(data => {
                this.notify(data.message, data.success);
                this.message.templates.push({
                    id: data.id,
                    name: formData.get('name'),
                    message: formData.get('message'),
                    checked: false
                });
            })).catch(() => {
                this.notify('Sorry Couldn\'t create template', false);
            });
        },
        deleteTemplate() {
            const toBeDeleted = this.message.templates.filter(template => {
                return template.checked;
            });

            if (!toBeDeleted.length) {
                return;
            }

            this.confirm(`${toBeDeleted.length} template${toBeDeleted.length > 1 ? 's' : ''} will be deleted!`)
                .then(confirm => {
                    if (!confirm) {
                        return;
                    }

                    const formData = new FormData();
                    formData.append('_token', window.saharaData.csrf);
                    formData.append('ids', toBeDeleted.map(template => template.id));

                    fetch(this.routes.messageTemplate.destroy, {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json().then(data => {
                        this.message.templates = this.message.templates.filter(template => {
                            return !template.checked;
                        });
                        this.notify(data.message, data.success);
                    })).catch(() => {
                        this.notify('Sorry Couldn\'t delete the templates', false);
                    });
                });
        },
        showEditTemplateModal() {
            let checked = this.message.templates.some(template => {
                if (!template.checked) {
                    return false;
                }

                this.templateModal.template = template;
                return true;
            });

            if (!checked) {
                return;
            }

            this.templateModal.type = 'edit';
            $('#template-modal').modal('show');
        },
        editTemplate() {
            $('#template-modal').modal('hide');
            const formData = new FormData(document.getElementById('edit-template-form'));
            formData.append('_token', window.saharaData.csrf);
            formData.append('_method', 'PUT');

            fetch(`${this.routes.messageTemplate.store}/${this.templateModal.template.id}`, {
                method: 'POST',
                body: formData
            }).then(response => response.json().then(data => {
                this.notify(data.message, data.success);
            })).catch(() => {
                this.notify('Sorry Couldn\'t update the template', false);
            });
        },
        sendSms() {
            const ids = this.filtered
                .filter(service => service.checked)
                .map(service => service.user_id);

            if (!ids.length) {
                return;
            }


            const body = new FormData();
            body.append('ids', ids.toString());
            body.append('message', this.message.sms);
            body.append('_token', window.saharaData.csrf);

            fetch(this.routes.sendSms, {
                method: 'POST',
                body: body
            }).then(response => response.text().then(data => {
                console.log(data);
            })).catch(() => this.notify('Sorry couldn\'t send sms!', false));
        },
        sendNotification() {
            const services = this.filtered.filter(service => service.checked);

            if (!services.length) {
                return;
            }


            const body = new FormData();

            services.forEach((service, index) => {
                body.append(`services[${index}][id]`, service.id);
                body.append(`services[${index}][type]`, service.type);
            });

            body.append('message', this.message.notification);
            body.append('_token', window.saharaData.csrf);

            fetch(this.routes.sendNotification, {
                method: 'POST',
                body: body
            }).then(response => response.text().then(data => {
                console.log(data);
            })).catch(() => this.notify('Sorry couldn\'t send notification!', false));
        },
        notify(message, success) {
            const options = {
                title: success ? 'Success' : 'Error',
                message: message,
                progressBar: false,
                position: 'topLeft'
            };

            success ? iziToast.success(options) : iziToast.error(options);
        },
        confirm(message, _default, title, noText, yesText,) {
            return new Promise(resolve => {

                iziToast.question({
                    timeout: 20000,
                    close: false,
                    overlay: true,
                    displayMode: 'once',
                    title: title ? title : 'Confirm',
                    message: message,
                    position: 'center',
                    buttons: [
                        [`<button>${yesText ? yesText : 'Ok'}</button>`, function (instance, toast) {

                            instance.hide({transitionOut: 'fadeOut'}, toast, 'ok');

                        }, true],
                        [`<button><b>${noText ? noText : 'Cancel'}</b></button>`, function (instance, toast) {

                            instance.hide({transitionOut: 'fadeOut'}, toast, 'cancel');

                        }],
                    ],
                    onClosing: function (instance, toast, closedBy) {
                        if (closedBy === 'timeout') {
                            resolve(_default === 'ok');
                        }

                        resolve(closedBy === 'ok');
                    }
                });
            });
        }
    },
    computed: {
        paginated() {
            if (this.loading) {
                return 0;
            }

            return Math.ceil(this.pagination.total / this.pagination.per_page);
        },
        templateModalTitle() {
            return this.templateModal.type === 'add' ? 'Add New Template' : this.templateModal.title;
        }
    },
    created() {
        fetchData(1);
    }
});

window.Echo = Echo;


form.addEventListener('submit', function (event) {
    event.preventDefault();
    fetchData(vm.filters.paged);
});

function fetchData(paged) {
    if (vm) {
        vm.loading = true;
    }

    const formData = new FormData(form);

    formData.append('page', paged);

    const fetchOption = {
        method: 'POST',
        body: formData
    };

    fetch(form.action, fetchOption).then(response => response.json().then(pagination => {
        if (!pagination.data.length) {
            let data = [];

            for (let service in pagination.data) {
                if (pagination.data.hasOwnProperty(service)) {
                    const provider = pagination.data[service];
                    provider.checked = false;

                    data.push(provider);
                }
            }

            pagination.data = data;
        } else {
            pagination.data.forEach((a, index) => pagination.data[index].checked = false);
        }


        vm.pagination = pagination;
        vm.filtered = pagination.data;
        vm.loading = false;
    }));
}
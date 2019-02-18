import '../modules/selectize-option-loader-plugin';
import '../../sass/backend/filter/index.scss';
import Vue from "vue";

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

        notifications: [],

        message: {
            sms: '',
            notification: '',
            language: 'bn',
            templates: window.saharaData.messageTemplates
        },

        templateModal: {
            title: '',
            message: '',
            type: 'show'
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
            const formData = new FormData(document.getElementById('add-template-form'));
            formData.append('_token', window.saharaData.csrf);

            fetch(this.routes.messageTemplate, {
                method: 'POST',
                body: formData
            }).then(response => response.json().then(data => {
                this.notify(data.message, data.success);
                this.message.templates.push({
                    name: formData.get('name'),
                    message: formData.get('message')
                });
            })).catch(() => {
                this.notify('Sorry Couldn\'t create template', false);
            });
        },
        sendSms() {
            const userIds = this.filtered
                .filter(service => service.checked)
                .map(service => service.user_id);

            const request = new XMLHttpRequest();

            request.open('POST', this.routes.sendSms);

            const body = new FormData();
            body.append('users', userIds);
            body.append('_token', window.saharaData.csrf);
            body.append('message', this.sms.message);

            request.onload = () => {
                console.log(JSON.parse(request.responseText));
            };

            request.send(body);
        },
        notify(message, success) {
            const id = Date.now() + '-' + Math.random();

            this.notifications.push({
                id: id,
                message: message,
                success: success
            });

            setTimeout(() => {
                this.notifications.some((notification, index) => {
                    if (notification.id !== id) {
                        return false;
                    }

                    this.notifications.splice(index, 1);

                    return true;
                });
            }, 10000);
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
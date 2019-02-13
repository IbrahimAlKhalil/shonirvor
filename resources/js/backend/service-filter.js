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
        routes: {
            asset: window.saharaRoutes.asset,
            individual: window.saharaRoutes.individual,
            organization: window.saharaRoutes.organization
        },

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
                within: 'none'
            }
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
            const start = this.filters.expiry.start;
            const end = this.filters.expiry.end;

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

            if (start && end) {
                filtered = filtered.filter(service => {
                    const st = new Date(start);
                    const en = new Date('en');

                    return true;
                });
            } else if (start && !end) {
                filtered = filtered.filter(service => {
                    const st = new Date(start);
                    const en = new Date('en');

                    return true;
                });
            }

            this.filtered = filtered;
        },
        checkAll() {
            this.filtered.forEach(service => {
                service.checked = !service.checked;
            });
        }
    },
    created() {
        fetchData();
    }
});


form.addEventListener('submit', function (event) {
    event.preventDefault();
    fetchData();
});

function fetchData() {
    if (vm) {
        vm.loading = true;
    }

    const fetchOption = {
        method: 'POST',
        body: new FormData(form)
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
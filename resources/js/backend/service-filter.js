import '../modules/selectize-option-loader-plugin';
import '../../sass/backend/filter/index.scss';
import Vue from "vue";

$('select').selectize({
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
        routes: {
            asset: window.saharaRoutes.asset,
            individual: window.saharaRoutes.individual,
            organization: window.saharaRoutes.organization
        }
    },
    computed: {
        services() {
            if (this.pagination.data.length) {
                return this.pagination.data;
            }

            const data = [];

            for (let service in this.pagination.data) {
                data.push(this.pagination.data[service]);
            }

            return data;
        }
    },
    methods: {
        getImage(image) {
            return `${this.routes.asset}/${image}`;
        },
        getServiceUrl(id, type) {
            return (type === 'ind' ? this.routes.individual : this.routes.organization) + '/' + id;
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

    fetch(form.action, fetchOption).then(response => response.json().then(data => {
        vm.pagination = data;
        vm.loading = false;
        for(let a in data.data) {
            console.log(data.data[a].payments.length);
            console.log(data.data[a].payments);
        }
    }));
}
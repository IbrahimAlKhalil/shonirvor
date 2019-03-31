import '../../../sass/frontend/chat.scss';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import BootstrapVue from 'bootstrap-vue';

import Vue from 'vue';
import index from './index.vue';

Vue.use(BootstrapVue);

document.addEventListener('DOMContentLoaded', function () {
    const vm = new Vue({
        render: h => h(index)
    }).$mount('main');
});



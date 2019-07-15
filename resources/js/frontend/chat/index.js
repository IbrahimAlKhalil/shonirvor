import '../../../sass/frontend/chat.scss'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './index.scss'

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Vuex from 'vuex'
import store from './store'
import index from './index.vue'

Vue.use(BootstrapVue)
Vue.use(Vuex)

Vue.prototype.$strollToBottom = () => {
    const elm = document.getElementById('chat-section')
    elm.scrollTop = elm.scrollHeight
}

document.addEventListener('DOMContentLoaded', function () {
    const vm = new Vue({
        store,
        render: h => h(index),
        created() {
            fetch(window.routes.getAccounts).then(response => response.json().then(data => {
                this.$store.commit('setAccounts', data)
                this.$store.commit('updateMeta')
                this.$store.commit('updateAccount')
            }))
        }
    }).$mount('main')
})



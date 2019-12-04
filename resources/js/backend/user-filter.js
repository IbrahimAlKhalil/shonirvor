import '../modules/selectize-option-loader-plugin';
import '../../sass/backend/filter/index.scss';
import 'izitoast/dist/css/iziToast.min.css';
import iziToast from 'izitoast';
import Vue from 'vue';
import Pusher from 'pusher-js';

const pusher = new Pusher('5a3bf510cd645f292be8', {
    cluster: 'ap2',
    forceTLS: true
});
const channel = pusher.subscribe('service-filter');

const form = document.getElementById('filter-form');

const vm = new Vue({
    el: '#root',
    data: {
        loading: true,
        pagination: {
            data: [],
            page: 1
        },
        filtered: [],
        routes: window.saharaRoutes,
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
        },
        progresses: [],
        sendingSms: false
    },
    methods: {
        checkAll(data) {
            data.forEach(item => {
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
                .filter(item => item.checked)
                .map(item => item.id);

            if (!ids.length) {
                return;
            }

            this.sendingSms = true;

            const body = new FormData();
            body.append('ids', ids.toString());
            body.append('message', this.message.sms);
            body.append('_token', window.saharaData.csrf);

            const progress = {
                total: ids.length,
                done: 0,
                message: 'Please Wait, Sending SMS...'
            };

            this.progresses.push(progress);

            channel.bind('SmsSent', (data) => {
                if (data.count === ids.length) {
                    channel.unbind('SmsSent');
                    this.progresses.splice(this.progresses.indexOf(progress), 1);
                    this.notify('SMS sent successfully!', true);
                    this.sendingSms = false;
                }
                progress.done = data.count;
            });

            const request = new XMLHttpRequest;
            request.open('POST', this.routes.sendSms);

            request.onerror = () => {
                this.notify('Sorry couldn\'t send SMS!', false);
                channel.unbind('SmsSent');
                this.sendingSms = false;
            };

            request.send(body);
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
        this.$nextTick(() => {
            fetchData(1);
        });
    }
});


form.addEventListener('submit', function (event) {
    event.preventDefault();
    fetchData(1);
});

function fetchData(paged) {
    if (vm) {
        vm.loading = true;
    }

    const formData = new FormData(form);

    const fetchOption = {
        method: 'POST',
        body: formData
    };

    fetch(`${form.action}?page=${paged}`, fetchOption).then(response => response.json().then(pagination => {
        pagination.data.forEach((a, index) => pagination.data[index].checked = false);

        vm.pagination = pagination;
        vm.filtered = pagination.data;
        vm.loading = false;
    }));
}

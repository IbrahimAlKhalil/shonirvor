import '../../../sass/frontend/registration/common.scss';
import '../../../../node_modules/smartwizard/dist/css/smart_wizard.css';
import '../../../../node_modules/smartwizard/dist/css/smart_wizard_theme_arrows.css';

import {ImagePicker} from "../../modules/image-picker";
import 'smartwizard';
import '../../modules/selectize-option-loader-plugin';

function clearSelectize(selectize) {
    selectize.clear();
    selectize.clearOptions();
    selectize.refreshOptions();
    selectize.refreshItems();
    selectize.clearCache();
    selectize.disable();
}

function text(input) {
    // this = inputMap item

    return `
       <dl>
           <dt class="d-inline text-capitalize">${this.name}:</dt>
           <dd class="d-inline ml-2">${input.value ? input.value : 'n/a'}</dd>
       </dl>`;
}


function partialSelect(input, form) {
    let str = $(input).find(':selected').text();

    this.selects.forEach(select => {
        const val = $(form).find(`[name="${select}"] :selected`).text();
        if (!val) {
            return;
        }
        str += `${str ? this.delimiter : ''}${val}`;
    });

    return `
       <dl>
           <dt class="d-inline text-capitalize">${this.name}:</dt>
           <dd class="d-inline ml-2">${str}</dd>
       </dl>`;
}


function select(input) {
    return `
       <dl>
           <dt class="d-inline text-capitalize">${this.name}:</dt>
           <dd class="d-inline ml-2">${$(input).find(':selected').text()}</dd>
       </dl>`;
}


const inputMap = {
    mobile: {
        name: 'মোবাইল নাম্বার',
        method: text
    },

    slug: {
        name: 'সার্ভিস লিঙ্ক',
        method: text
    },

    description: {
        name: 'নিজের সম্পর্কে',
        method: text
    },

    referrer: {
        name: 'রেফারার',
        method: text
    },

    email: {
        name: 'ইমেইল',
        method: text
    },

    website: {
        name: 'ওয়েবসাইট',
        method: text
    },

    facebook: {
        name: 'ফেসবুক',
        method: text
    },

    qualification: {
        name: 'শিক্ষাগত যোগ্যতা',
        method: text
    },

    nid: {
        name: 'জাতীয় পরিচয়পত্রের নম্বর',
        method: text
    },

    address: {
        name: 'পূর্ণাঙ্গ ঠিকানা',
        method: text
    },

    from: {
        name: 'যে নাম্বার থেকে পাঠানো হয়েছে',
        method: text
    },

    'transaction-id': {
        name: 'Transaction ID',
        method: text
    },

    day: {
        name: 'জন্ম তারিখ',
        selects: ['month', 'year'],
        delimiter: ' ',
        method: partialSelect
    },

    'package': {
        name: 'প্যাকেজ',
        method: select
    },

    'payment-method': {
        name: 'পেমেন্ট এর মাধ্যম',
        method: select
    },

    village: {
        name: 'ঠিকানা',
        selects: ['village', 'union', 'thana', 'district', 'division'],
        delimiter: ', ',
        method: partialSelect
    },

    category: {
        name: 'সেবার ধরন',
        method: select
    }
};


$(document).ready(function () {


    new ImagePicker(document.getElementsByClassName('file-picker'));

    let checkbox = $('#no-sub-category');
    checkbox.next().on('click', function () {
        checkbox[0].checked = !checkbox[0].checked;
    });

    let form = document.getElementById('registration-form');
    let moForm = document.getElementById('mo-registration-form');
    let validation = $(form).validate();
    let requiredSelects = $('#division, #district');
    let prev = $('.sw-btn-prev');
    let next = $('.sw-btn-next');

    const modal = $('#verify-data');
    const dataContainer = modal.find('.modal-body');

    $('#submit-btn').on('click', function () {
        let $package = $('#package');
        if (!$package.val()) {
            $package.next().find('.selectize-input').addClass('border-danger');
            return;
        }

        dataContainer.empty();
        $('#data-correct').attr('form', 'registration-form');

        const elements = form.elements;

        [...elements].forEach(element => {
            if (element.name in inputMap)
                dataContainer.append(inputMap[element.name].method(element, form));
        });

        modal.modal('show');
    });


    $('#mo-submit-btn').on('click', function () {
        let $package = $('#mo-package');
        if (!$package.val()) {
            $package.next().find('.selectize-input').addClass('border-danger');
            return;
        }

        dataContainer.empty();
        $('#data-correct').attr('form', 'mo-registration-form');

        const elements = moForm.elements;

        [...elements].forEach(element => {
            if (element.name in inputMap)
                dataContainer.append(inputMap[element.name].method(element, moForm));
        });

        modal.modal('show');
    });

    prev.addClass('invisible');
    let steps = {
        step2: [
            {
                select: $('#thana'),
                check: $('#no-thana')
            },
            {
                select: $('#union'),
                check: $('#no-union')
            },
            {
                select: $('#village'),
                check: $('#no-village')
            }
        ],
        step3: [
            {
                select: $('#category'),
                check: $('#no-category')
            }
        ]
    };

    let selectsAndChecks = [
        ...steps.step2.map(obj => {
            return obj;
        }),
        ...steps.step3.map(obj => {
            return obj;
        })];


    function validateSelects(step) {
        return !step.some(obj => {
            if (!obj.select.val() && !obj.check[0].checked) {
                obj.select.next().find('.selectize-input').addClass('border-danger');
                return true;
            }

            obj.select.next().find('.selectize-input').removeClass('border-danger');
            return false;
        });
    }

    selectsAndChecks.forEach(obj => {
        obj.check[0].addEventListener('change', function () {
            if (!obj.check[0].checked) {
                validation.checkForm();
                validation.showErrors();
                return;
            }

            obj.select.val(null);
            let selectize = obj.select[0].selectize;
            selectize.clear(true);
            if (obj.select[0].hasAttribute('data-option-loader-target')) {
                let target = document.querySelector(obj.select[0].getAttribute('data-option-loader-target'));

                while (target) {
                    clearSelectize(target.selectize);
                    target = document.querySelector(target.getAttribute('data-option-loader-target'));
                }
            }
        });
    });

    [...selectsAndChecks.map(obj => obj.select[0]), ...requiredSelects.toArray()].forEach(select => {
        select.selectize.on('change', value => {
            if (!!value) {
                $(select).next().find('.selectize-input').removeClass('border-danger');
            }
        });
    });

    $('#smartwizard').on('leaveStep', function (e, anchor, stepNumber, direction) {

        if (direction !== 'forward') {
            return true;
        }

        if (!validation.checkForm()) {
            validation.errorList.forEach(error => {
                error.element.setAttribute('aria-invalid', 'true')
            });
            validation.showErrors();
            return false;
        }

        console.log(validation.checkForm());

        if (stepNumber === 1) {
            let requiredSelectsNotFilled = requiredSelects.toArray().some(select => {
                return !select.value;
            });

            if (requiredSelectsNotFilled) {
                requiredSelects.each(function () {
                    $(this).next().find('.selectize-input').addClass('border-danger');
                });
                return false;
            }

            if (!validateSelects(steps.step2)) {
                return false;
            }
        }

        if (stepNumber === 2) {
            if (!validateSelects(steps.step3)) {
                return false;
            }
        }


        requiredSelects.each(function () {
            $(this).next().find('.selectize-input').removeClass('border-danger');
        });
        return true;
    })
        .on('showStep', function (event, anchor, stepNumber) {

            if (stepNumber === 0) {
                prev.addClass('invisible');
            }
            if (stepNumber === 4) {
                next.addClass('invisible')
            }


            if (stepNumber !== 0) {
                prev.removeClass('invisible');
            }
            if (stepNumber !== 4) {
                next.removeClass('invisible');
            }
        });
    window.selectizes = [];

    function savePaymentMethod(prefix) {
        let selectize = $(`#${prefix}payment-method`)[0].selectize;

        selectize.on('change', function (value) {
            localStorage.setItem('paymentMethod', value);
            $(`#${prefix}payment-method-accountId span`).addClass('d-none');
            $(`#${prefix}payment-method-id-${value}`).removeClass('d-none');
        });
        selectize.setValue(localStorage.paymentMethod);
    }

    function savePackage(prefix) {
        let selectize = $(`#${prefix}package`)[0].selectize;

        selectize.on('change', function (value) {
            localStorage.setItem('package', value);
            $(`#${prefix}package-descriptions div`).removeClass('active show');
            $(`#${prefix}package-dscr-${value}`).addClass('active show');
        });

        selectize.setValue(localStorage.paymentMethod);
    }

    savePaymentMethod('mo-');
    savePaymentMethod('');
    savePackage('mo-');
    savePackage('');

});

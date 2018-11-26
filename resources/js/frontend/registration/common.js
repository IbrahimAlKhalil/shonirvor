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

$(document).ready(function () {


    new ImagePicker(document.getElementsByClassName('file-picker'));

    let checkbox = $('#no-sub-category');
    checkbox.next().on('click', function () {
        checkbox[0].checked = !checkbox[0].checked;
    });

    let form = document.getElementById('registration-form');
    let validation = $(form).validate();
    let requiredSelects = $('#division, #district');
    let prev = $('.sw-btn-prev');
    let next = $('.sw-btn-next');

    form.addEventListener('submit', function (evt) {
        let $package = $('#package');
        if (!$package.val()) {
            $package.next().find('.selectize-input').addClass('border-danger');
            evt.preventDefault();
        }
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

    function savePackage(prefix) {

        $(`#${prefix}payment-method`)[0].selectize.on('change', value => {
            localStorage.setItem('paymentMethod', value);
            $(`#${prefix}payment-method-accountId`).find('span').addClass('d-none');
            $(`#${prefix}payment-method-id-${value}`).removeClass('d-none')
        });
        document.querySelector(`#${prefix}payment-method`).selectize.setValue(localStorage.paymentMethod);
    }

    savePackage('mo-');
    savePackage('');

});
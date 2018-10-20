import '../../../scss/frontend/registration/common.scss';

$(document).ready(function () {

    let form = document.getElementById('registration-form');
    let validation = $(form).validate();
    let requiredSelects = $('#division, #district');
    let prev = $('.sw-btn-prev');
    let next = $('.sw-btn-next');
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

    $(selectsAndChecks.map(obj => obj.check)).on('change', function () {
        if (!this.checked) {
            validation.checkForm();
            validation.showErrors();
        }
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
            if (stepNumber === 3) {
                next.addClass('invisible')
            }


            if (stepNumber !== 0) {
                prev.removeClass('invisible');
            }
            if (stepNumber !== 3) {
                next.removeClass('invisible');
            }
        });
});
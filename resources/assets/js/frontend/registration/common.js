import '../../../scss/frontend/registration/common.scss';

$(document).ready(function () {

    let form = document.getElementById('registration-form');
    let validation = $(form).validate();
    let prev = $('.sw-btn-prev');
    let next = $('.sw-btn-next');
    prev.addClass('invisible');

    $('#no-thana, #no-union, #no-village, #no-category').on('change', function () {
        if (!this.checked) {
            validation.checkForm();
            validation.hideErrors();
        }
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

        // error de4e6d
        // valid b8b8b8

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
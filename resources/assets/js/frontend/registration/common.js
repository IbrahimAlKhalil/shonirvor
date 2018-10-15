import '../../../scss/frontend/registration/common.scss';

document.addEventListener('DOMContentLoaded', () => {

    let prev = $('.sw-btn-prev');
    let next = $('.sw-btn-next');
    prev.addClass('invisible');


    $('#smartwizard').on('leaveStep', function (e, anchor, stepNumber, direction) {

        if (direction !== 'forward') {
            return true;
        }

        var hasError = false;
        var hasEmpty = $(anchor.attr('href')).find('[aria-required="true"]').toArray().some(element => {
            if (!element.value) {
                element.setAttribute('aria-invalid', true);
                return true;
            }

            return false;
        });

        hasEmpty = $(anchor.attr('href')).find('select').toArray().some(select => {
            if (!select.value) {
                console.log($(select).next().find('.selectize-input'));
                $(select).next().find('.selectize-input').css('borderColor', '#de4e6d');
                return true;
            }
        });

        if (!!$(anchor.attr('href')).find('[aria-invalid="true"]').length) {
            hasError = true;
        }

        if (hasError || hasEmpty) {
            anchor.parent().addClass('danger');
            return false;
        }

        anchor.parent().removeClass('danger');
        $(anchor.attr('href')).find('select').each(function () {
            $(this).next().find('.selectize-input').css('borderColor', '#b8b8b8');
        });

        return true;
    })
        .on('showStep', function (event, anchor, stepNumber) {

            if (stepNumber === 0) {
                prev.addClass('invisible');
            }
            if(stepNumber === 3) {
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
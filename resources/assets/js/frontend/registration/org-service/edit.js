import {Repeater} from '../../../modules/repeater';
import {OptionLoader} from "../../../modules/option-loader";
import 'smartwizard';
import '../../../modules/selectize-option-loader-plugin';

import '../../../../../../node_modules/selectize/dist/css/selectize.default.css';
import '../../../../../../node_modules/smartwizard/dist/css/smart_wizard.css';
import '../../../../../../node_modules/smartwizard/dist/css/smart_wizard_theme_arrows.css';

document.addEventListener('DOMContentLoaded', () => {
    let select = document.getElementById('sub-categories');

    let repeater = new Repeater(document.getElementById('repeater-container'));
    let repeater2 = new Repeater(document.getElementById('req-repeater-container'));

    $('.add-new').on('click', function () {
        repeater2.repeat((item) => {
            $(item).find('.remove-btn').removeClass('d-none').addClass('d-flex');
            $(item).find('.sub-category-name').attr('name', `sub-category-requests[${repeater2.length}][name]`);
            $(item).find('.sub-category-rate').attr('name', `sub-category-requests[${repeater2.length}][rate]`);
        });
    });

    select.selectize.on('change', values => {
        repeater.removeAll();
        if (values.length) {
            values.forEach(value => {
                repeater.repeat((item) => {
                    $(item).removeClass('d-none');
                    $(item).find('label').attr('for', `sub-category-${repeater.length - 1}-${value}`).text($(select).find(`[value="${value}"]`).text());
                    $(item).find('input').eq(0).attr('id', `sub-category-${repeater.length - 1}-${value}`).attr('name', `sub-categories[${repeater.length - 1}][rate]`);
                    $(item).find('input').eq(1).attr('name', `sub-categories[${repeater.length - 1}][id]`).val(value);
                });
            });
        }
    });
});


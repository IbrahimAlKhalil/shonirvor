import {Repeater} from '../../../modules/repeater';

document.addEventListener('DOMContentLoaded', () => {
    let select = document.getElementById('sub-categories');
    let subCategoryContainer = document.getElementById('repeater-container');

    let subCategoryRepeater = new Repeater(subCategoryContainer);
    let subCategoryRequestRepeater = new Repeater(document.getElementById('req-repeater-container'));

    $(subCategoryContainer).find('.add-new').on('click', function () {
        subCategoryRequestRepeater.repeat((item) => {
            $(item).find('.remove-btn').removeClass('d-none').addClass('d-flex');
            $(item).find('.sub-category-name').attr('name', `sub-category-requests[${subCategoryRequestRepeater.length}][name]`);
            $(item).find('.sub-category-rate').attr('name', `sub-category-requests[${subCategoryRequestRepeater.length}][rate]`);
        });
    });

    select.selectize.on('change', values => {
        subCategoryRepeater.removeAll();
        if (values.length) {
            values.forEach(value => {
                subCategoryRepeater.repeat((item) => {
                    $(item).removeClass('d-none');
                    $(item).find('label').attr('for', `sub-category-${subCategoryRepeater.length - 1}-${value}`).text($(select).find(`[value="${value}"]`).text());
                    $(item).find('input').eq(0).attr('id', `sub-category-${subCategoryRepeater.length - 1}-${value}`).attr('name', `sub-categories[${subCategoryRepeater.length - 1}][rate]`);
                    $(item).find('input').eq(1).attr('name', `sub-categories[${subCategoryRepeater.length - 1}][id]`).val(value);
                });
            });
        }
    });
});


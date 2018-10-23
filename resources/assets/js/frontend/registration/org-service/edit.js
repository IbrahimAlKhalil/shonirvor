import {Repeater} from '../../../modules/repeater';

document.addEventListener('DOMContentLoaded', () => {
    let select = document.getElementById('sub-categories');
    let subCategoryContainer = document.getElementById('req-repeater-container');
    let additionalPricingContainer = document.getElementById('otirikto-kaj');

    let subCategoryRepeater = new Repeater(document.getElementById('repeater-container'));
    let subCategoryRequestRepeater = new Repeater(subCategoryContainer);
    let additionalPricingRepeater = new Repeater(additionalPricingContainer);

    additionalPricingContainer.querySelector('.add-new').addEventListener('click', () => {
        additionalPricingRepeater.repeat(item => {
            let nameInput = item.querySelector('input');
            let nameInputId = `additional-pricing-name-${additionalPricingRepeater.length}`;
            item.firstElementChild.querySelector('label').setAttribute('for', nameInputId);
            nameInput.id = nameInputId;
            nameInput.name = `additional-pricing[${additionalPricingRepeater.length}][name]`;

            let infoInput = item.querySelector('textarea');
            let infoInputId = `additional-pricing-info-${additionalPricingRepeater.length}`;
            item.children[1].querySelector('label').setAttribute('for', infoInputId);
            infoInput.id = infoInputId;
            infoInput.name = `additional-pricing[${additionalPricingRepeater.length}][info]`;
        });
    });

    $(subCategoryContainer).find('.add-new').on('click', function () {
        subCategoryRequestRepeater.repeat(item => {
            console.log(subCategoryRequestRepeater);
            $(item).removeClass('d-none');
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


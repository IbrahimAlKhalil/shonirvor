import {Repeater} from '../../../modules/repeater';

document.addEventListener('DOMContentLoaded', () => {
    let select = document.getElementById('sub-categories');
    let subCategoryRequestContainer = document.getElementById('req-repeater-container');
    let otiriktoKajContainer = document.getElementById('otirikto-kaj');

    let repeater = new Repeater(document.getElementById('repeater-container'));
    let repeater2 = new Repeater(subCategoryRequestContainer);
    let repeater3 = new Repeater(otiriktoKajContainer);

    subCategoryRequestContainer.querySelector('.add-new').addEventListener('click', () => {
        repeater2.repeat(item => {
            $(item).find('.remove-btn').removeClass('d-none').addClass('d-flex');
            $(item).find('.sub-category-name').attr('name', `sub-category-requests[${repeater2.length}][name]`);
            $(item).find('.sub-category-rate').attr('name', `sub-category-requests[${repeater2.length}][rate]`);
        });
    });

    otiriktoKajContainer.querySelector('.add-new').addEventListener('click', () => {
        repeater3.repeat(item => {
            let nameInput = item.querySelector('input');
            let nameInputId = `additional-pricing-name-${repeater3.length}`;
            item.firstElementChild.querySelector('label').setAttribute('for', nameInputId);
            nameInput.id = nameInputId;
            nameInput.name = `additional-pricing[${repeater3.length}][name]`;

            let infoInput = item.querySelector('textarea');
            let infoInputId = `additional-pricing-info-${repeater3.length}`;
            item.children[1].querySelector('label').setAttribute('for', infoInputId);
            infoInput.id = infoInputId;
            infoInput.name = `additional-pricing[${repeater3.length}][info]`;
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


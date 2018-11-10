import {Repeater} from '../../../modules/repeater';

document.addEventListener('DOMContentLoaded', () => {

    $('[data-repeater-clone]').each(function () {
        let card = $(this);
        card.find('.remove-btn').on('click', function (event) {
            event.preventDefault();
            $(card).fadeOut('slow', function () {
                $(card).remove();
            });
        });
    });


    /************************ Additional pricing ***********************/

    (function () {
        let container = document.getElementById('otirikto-kaj');

        let repeater = new Repeater(container, function () {
            let length = this.count;
            let fragment = document.createElement('div');
            fragment.innerHTML = `
            <div class="row border rounded shadow-sm mt-2 position-relative">
                <div class="form-group  col-md-12 row mt-3">
                    <label for="additional-pricing-name-${length}" class="col-3 col-form-label">কাজের
                        নামঃ </label>
                    <div class="col-9">
                        <input id="additional-pricing-name-${length}" type="text"
                               name="additional-pricing[${length}][name]"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group  col-md-12 row mt-2">
                    <label for="additional-pricing-info-${length}" class="col-3 col-form-label">তথ্যঃ </label>
                    <div class="col-9">
                        <textarea id="additional-pricing-info-${length}" name="additional-pricing[${length}][info]"
                                                      class="form-control"></textarea>
                    </div>
                </div>

                <span class="cross remove-btn"></span>
            </div>
            `;

            let div = fragment.firstElementChild.cloneNode(true);
            $(div).find('.remove-btn').on('click', function (event) {
                event.preventDefault();
                $(div).fadeOut('slow', function () {
                    $(div).remove();
                });
            });

            return div;
        });

        container.querySelector('.add-new').addEventListener('click', () => {
            repeater.repeat();
        });
    })();


    /*********************** Sub category request ************************/

    (function () {
        let container = document.getElementById('req-repeater-container');

        let repeater = new Repeater(container, function () {
            let length = this.count;

            let fragment = document.createElement('ul');
            fragment.innerHTML = `
            <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                <div class="row">
                    <input type="text" class="form-control col-md-5"
                           name="sub-category-requests[${length}][name]"
                           placeholder="সাব-ক্যাটাগরির নাম">
                    <input type="number" class="form-control col-md-5"
                           name="sub-category-requests[${length}][rate]"
                           placeholder="রেট">
                    <a class="fa fa-trash col-md-2 align-items-center float-right text-danger delete-image remove-btn d-flex"
                       href="#"></a>
                </div>
            </li>
            `;

            let li = fragment.firstElementChild.cloneNode(true);
            $(li).find('.remove-btn').on('click', function (event) {
                event.preventDefault();
                $(li).fadeOut('slow', function () {
                    $(li).remove();
                });
            });

            return li;
        });

        container.querySelector('.add-new').addEventListener('click', () => {
            repeater.repeat();
        });
    })();


    /*************************** Sub category *******************************/
    (function () {
        let container = document.getElementById('repeater-container');

        let repeater = new Repeater(container, function (value) {
            let length = this.count;
            let fragment = document.createElement('ul');
            fragment.innerHTML = `
            <li class="repeater-clone mt-2 border-0 list-group-item">
                <div class="row">
                    <label class="col-md-6" for="sub-category-${length - 1}-${value}">${$(select).find(`[value="${value}"]`).text()}</label>
                    <input type="number"
                           id="sub-category-${length - 1}-${value}"
                           name="sub-categories[${length - 1}][rate]"
                           class="form-control col-md-6"
                           placeholder="রেট">
                    <input type="hidden" name="sub-categories[${length - 1}][id]" value="${value}">
                </div>
            </li>
        `;

            return fragment.firstElementChild.cloneNode(true);
        });

        let select = document.getElementById('sub-categories');
        select.selectize.on('change', values => {
            repeater.removeAll();
            if (values.length) {
                values.forEach(value => {
                    repeater.repeat([value]);
                });
            }
        });
    })();
});


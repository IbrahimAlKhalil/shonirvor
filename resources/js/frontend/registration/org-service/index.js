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
    function priceRepeater(container, addNew) {
        let repeater = new Repeater(container, function () {
            let length = this.count;
            let fragment = document.createElement('div');
            fragment.innerHTML = `
            <div class="border rounded shadow-sm mt-2 position-relative">
                <div class="form-group col-md-12 mt-3">
                    <label for="additional-pricing-name-${length}" class="col-form-label">কাজের
                        নামঃ </label>
                    <input id="additional-pricing-name-${length}" type="text"
                               name="additional-pricing[${length}][name]"
                               class="form-control">
                </div>

                <div class="form-group  col-md-12 mt-2">
                    <label for="additional-pricing-info-${length}" class="col-form-label">তথ্যঃ </label>
                    <textarea id="additional-pricing-info-${length}" name="additional-pricing[${length}][info]"
                                                      class="form-control"></textarea>
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

        addNew.addEventListener('click', () => {
            repeater.repeat();
        });
    }

    priceRepeater(document.getElementById('otirikto-kaj'), document.getElementById('add-new-price'));

    priceRepeater(document.getElementById('mo-otirikto-kaj'), document.getElementById('mo-add-new-price'));


    /*********************** Sub category request ************************/

    function subReqRepeater(container, addNew) {
        let repeater = new Repeater(container, function () {
            let length = this.count;

            let fragment = document.createElement('ul');
            fragment.innerHTML = `
            <li class="mt-2 border-0 list-group-item" data-repeater-clone="true">
                <div class="row">
                    <input type="text" class="form-control col-5"
                           name="sub-category-requests[${length}][name]"
                           placeholder="সাব-ক্যাটাগরির নাম">
                    <input type="number" class="form-control col-5"
                           name="sub-category-requests[${length}][rate]"
                           placeholder="রেট">
                    <a class="fa fa-trash col-2 align-items-center float-right text-danger delete-image remove-btn d-flex"
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

        addNew.addEventListener('click', () => {
            repeater.repeat();
        });
    }

    subReqRepeater(document.getElementById('sub-req-repeater-container'), document.getElementById('add-new-sub'));

    let reqContainer = document.getElementById('mo-sub-req-repeater-container');
    let reqAddNew = document.getElementById('mo-add-new-sub');
    subReqRepeater(reqContainer, reqAddNew);

    $('#mo-no-sub-category').on('click', function () {
        $([reqContainer, reqAddNew]).toggleClass('d-none');
    });

    /*************************** Sub category *******************************/

    function subRepeater(container, select) {
        let repeater = new Repeater(container, function (value) {
            let length = this.count;
            let fragment = document.createElement('ul');
            fragment.innerHTML = `
            <li class="repeater-clone mt-2 border-0 list-group-item">
                <div class="row">
                    <label class="col-form-label" for="sub-category-${length - 1}-${value}">${$(select).find(`[value="${value}"]`).text()}</label>
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

        select.selectize.on('change', values => {
            repeater.removeAll();
            if (values.length) {
                values.forEach(value => {
                    repeater.repeat([value]);
                });
            }
        });
    }

    subRepeater(document.getElementById('sub-repeater-container'), document.getElementById('sub-categories'));
    subRepeater(document.getElementById('mo-sub-repeater-container'), document.getElementById('mo-sub-categories'))
});


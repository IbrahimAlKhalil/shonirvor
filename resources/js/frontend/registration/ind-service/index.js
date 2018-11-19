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

    let container = document.getElementById('sub-category-parent');

    fetch(container.getAttribute('data-route')).then(response => response.json()).then(workMethods => {

        /******************************  Sub category request repeter **********************************/
        function subCategoryReq(container, addNew) {
            let repeater = new Repeater(container, function () {
                let cardBody = '';
                let serial = this.count;

                workMethods.forEach((method, count) => {
                    if (method.id === 4) {
                        cardBody += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${method.id}-${serial}" class="checkbox">${method.name}
                                        <input type="checkbox" id="req-work-method-${method.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${count}][checkbox]">
                                        <span></span>
                                    </label>
                                    <input type="hidden" name="sub-category-requests[${serial}][work-methods][${count}][id]" value="${method.id}">
                                </div>
                            </div>`;
                        return;
                    }

                    cardBody += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${method.id}-${serial}" class="checkbox">${method.name}
                                        <input type="checkbox" id="req-work-method-${method.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${count}][checkbox]">
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" placeholder="রেট" name="sub-category-requests[${serial}][work-methods][${count}][rate]">
                                    <input type="hidden" name="sub-category-requests[${serial}][work-methods][${count}][id]" value="${method.id}">
                                </div>
                            </div>`;
                });

                let fragment = document.createElement('div');
                fragment.innerHTML = `
                <div class="card mt-2">
                    <div class="card-header pt-2 m-0 row">
                        <div class="col-9">
                            <input type="text" class="form-control" name="sub-category-requests[${serial}][name]" placeholder="সাব-ক্যাটাগরির নাম">
                        </div>
                        <div class="col-3">
                            <a class="fa fa-trash float-right text-danger remove-btn"
                               href="#"></a>
                        </div>
                    </div>
                    <div class="card-body">
                        ${cardBody}
                    </div>
                </div>
            `;
                let card = fragment.firstElementChild.cloneNode(true);
                $(card).find('.remove-btn').on('click', function (event) {
                    event.preventDefault();
                    $(card).fadeOut('slow', function () {
                        $(card).remove();
                    });
                });

                return card;
            });

            addNew.addEventListener('click', () => {
                repeater.repeat();
            });
        }


        let moSubReq = document.getElementById('mo-sub-category-request');
        let moNoSub = document.getElementById('mo-no-sub-category');

        subCategoryReq(document.getElementById('sub-category-request'), document.getElementById('add-new'));
        subCategoryReq(moSubReq, document.getElementById('mo-add-new'));

        moNoSub.addEventListener('change', function () {
            $(moSubReq).toggleClass('d-none');
        });

        /*******************************  Sub category  ********************************/

        function subCategory(container, select) {
            let repeater = new Repeater(container, function (id, count) {
                let cardBody = '';

                workMethods.forEach((workMethod, MethodCount) => {
                    if (workMethod.id === 4) {
                        cardBody += `
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label for="work-method-${workMethod.id}-${count}" class="checkbox">${workMethod.name}
            <input type="checkbox" id="work-method-${workMethod.id}-${count}" name="sub-category-rates[${count}][work-methods][${MethodCount}][checkbox]">
                <span></span>
                </label>
                <input type="hidden" name="sub-category-rates[${count}][id]" value="${id}">
                <input type="hidden" name="sub-category-rates[${count}][work-methods][${MethodCount}][id]" value="${workMethod.id}">
                </div>
                </div>`;
                        return;
                    }

                    cardBody += `
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label for="work-method-${workMethod.id}-${count}" class="checkbox">${workMethod.name}
                                    <input type="checkbox" id="work-method-${workMethod.id}-${count}" name="sub-category-rates[${count}][work-methods][${MethodCount}][checkbox]">
                                    <span></span>
                                </label>
                                <input type="hidden" name="sub-category-rates[${count}][id]" value="${id}">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" placeholder="রেট" name="sub-category-rates[${count}][work-methods][${MethodCount}][rate]">
                                <input type="hidden" name="sub-category-rates[${count}][work-methods][${MethodCount}][id]" value="${workMethod.id}">
                            </div>
                        </div>`;
                });


                let fragment = document.createElement('div');
                fragment.innerHTML =
                    `<div class="card mt-2">
                    <div class="card-header pb-0 pt-2">${select.querySelector(`[value='${id}']`).innerHTML}</div>
                    <div class="card-body">
                          ${cardBody}
                    </div>
                </div>`;
                return fragment.firstElementChild.cloneNode(true);
            });

            select.selectize.on('change', values => {
                repeater.removeAll();
                values.forEach((value, count) => {
                    if (!value) return;
                    repeater.repeat([value, count]);
                });
            });
        }

        subCategory(document.getElementById('sub-category-parent'), document.getElementById('sub-categories'));
        subCategory(document.getElementById('mo-sub-category-parent'), document.getElementById('mo-sub-categories'));
    });
});
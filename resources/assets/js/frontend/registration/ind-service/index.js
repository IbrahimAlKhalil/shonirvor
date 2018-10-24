import {Repeater} from '../../../modules/repeater';

document.addEventListener('DOMContentLoaded', () => {

    $('[data-repeater-clone]').each(function () {
        let card = $(this);
        card.find('.remove-btn').on('click', function () {
            $(card).fadeOut('slow', function () {
                $(card).remove();
            });
        });
    });

    let container = document.getElementById('sub-category-parent');

    fetch(container.getAttribute('data-route')).then(response => response.json()).then(workMethods => {

        /******************************  Sub category request repeter **********************************/

        (function () {
            let container = document.getElementById('sub-category-request');
            let repeater = new Repeater(container, function () {
                let cardBody = '';
                let serial = this.length - 1;

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

                let virtualDom = document.createElement('div');
                virtualDom.innerHTML = `
                <div class="card mt-2">
                    <div class="card-header pt-2 m-0 row">
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="sub-category-requests[${serial}][name]" placeholder="আমার সাব-ক্যাটাগরির নাম">
                        </div>
                        <div class="col-md-3">
                            <a class="fa fa-trash float-right text-danger remove-btn"
                               href="#"></a>
                        </div>
                    </div>
                    <div class="card-body">
                        ${cardBody}
                    </div>
                </div>
            `;
                let card = virtualDom.firstElementChild.cloneNode(true);
                $(card).find('.remove-btn').on('click', function (event) {
                    event.preventDefault();
                    $(card).fadeOut('slow', function () {
                        $(card).remove();
                    });
                });

                return card;
            });

            document.getElementById('add-new').addEventListener('click', () => {
                repeater.repeat();
            });
        })();

        /*******************************  Sub category  ********************************/

        (function () {
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


                let virtualDom = document.createElement('div');
                virtualDom.innerHTML =
                    `<div class="card mt-2">
                    <div class="card-header pb-0 pt-2">${subCategorySelect.querySelector(`[value='${id}']`).innerHTML}</div>
                    <div class="card-body">
                          ${cardBody}
                    </div>
                </div>`;
                return virtualDom.firstElementChild.cloneNode(true);
            });

            let subCategorySelect = document.getElementById('sub-categories');
            subCategorySelect.selectize.on('change', values => {
                repeater.removeAll();
                values.forEach((value, count) => {
                    if (!value) return;
                    repeater.repeat([value, count]);
                });
            });
        })();


    });
});
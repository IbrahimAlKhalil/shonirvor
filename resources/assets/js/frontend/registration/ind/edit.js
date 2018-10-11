import {Repeater} from '../../../modules/repeater';
import {OptionLoader} from "../../../modules/option-loader";


function requestFields(element, workMethods, serial) {
    workMethods.forEach((workMethod, workMethodCount) => {
        if (workMethod.id === 4) {
            element.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${workMethod.id}-${serial}">${workMethod.name}</label>
                                    <input type="checkbox" id="req-work-method-${workMethod.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][checkbox]">
                                    <input type="hidden" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
            return;
        }

        element.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${workMethod.id}-${serial}">${workMethod.name}</label>
                                    <input type="checkbox" id="req-work-method-${workMethod.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][checkbox]">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="রেট" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][rate]">
                                    <input type="hidden" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
    });
}


document.addEventListener('DOMContentLoaded', () => {
    let container = document.getElementById('sub-categories-parent');
    let requestContainer = document.getElementById('sub-category-request');
    let repeater = new Repeater(container);
    let repeater2 = new Repeater(requestContainer);

    fetch(container.getAttribute('data-route')).then(response => response.json()).then(workMethods => {

        document.getElementById('add-new').addEventListener('click', () => {
            repeater2.repeat(function (item) {
                item.querySelector('.remove-btn').classList.remove('d-none');
                item.lastElementChild.innerHTML = '';
                requestFields(item.lastElementChild, workMethods, repeater2.length);

                item.firstElementChild.firstElementChild.innerHTML = `<input type="text" class="form-control" name="sub-category-requests[${repeater2.length}][name]" placeholder="আমার সাব-ক্যাটাগরির নাম">`;
            });
        });


        document.getElementById('sub-categories').addEventListener('change', event => {
            repeater.removeAll();

            $(event.target).val().forEach((subCategoryId, subCategoryCount) => {
                if (!subCategoryId) return;
                repeater.repeat(function (item) {
                    item.classList.remove('d-none');
                    item.firstElementChild.innerHTML = event.target.querySelector(`[value='${subCategoryId}']`).innerHTML;
                    workMethods.forEach((workMethod, workMethodCount) => {
                        if (workMethod.id === 4) {
                            item.lastElementChild.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="work-method-${workMethod.id}-${subCategoryCount}">${workMethod.name}</label>
                                    <input type="checkbox" id="work-method-${workMethod.id}-${subCategoryCount}" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][checkbox]">
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][id]" value="${subCategoryId}">
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
                            return;
                        }

                        item.lastElementChild.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="work-method-${workMethod.id}-${subCategoryCount}">${workMethod.name}</label>
                                    <input type="checkbox" id="work-method-${workMethod.id}-${subCategoryCount}" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][checkbox]">
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][id]" value="${subCategoryId}">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="রেট" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][rate]">
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
                    });
                });
            });
        });
    });
});
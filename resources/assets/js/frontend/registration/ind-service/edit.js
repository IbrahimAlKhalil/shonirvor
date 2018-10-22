import {Repeater} from '../../../modules/repeater';
import {querySelectorAll} from "../../../modules/scoped-query-selector";

function requestFields(element, workMethods, serial) {
    workMethods.forEach((workMethod, workMethodCount) => {
        if (workMethod.id === 4) {
            element.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${workMethod.id}-${serial}" class="checkbox">${workMethod.name}
                                        <input type="checkbox" id="req-work-method-${workMethod.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][checkbox]">
                                        <span></span>
                                    </label>
                                    <input type="hidden" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
            return;
        }

        element.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="req-work-method-${workMethod.id}-${serial}" class="checkbox">${workMethod.name}
                                        <input type="checkbox" id="req-work-method-${workMethod.id}-${serial}" name="sub-category-requests[${serial}][work-methods][${workMethodCount}][checkbox]">
                                        <span></span>
                                    </label>
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

    console.log(querySelectorAll(requestContainer, '> [data-cloned]'));

    // sub category rate
    let subCategoryRateRepeater = new Repeater(container);

    // sub category request
    let subCategoryRequestRepeater = new Repeater(requestContainer);

    subCategoryRequestRepeater.clones.forEach(clone => {
        clone.previousClone = clone.previousElementSibling;

    });


    fetch(container.getAttribute('data-route')).then(response => response.json()).then(workMethods => {

        document.getElementById('add-new').addEventListener('click', () => {
            subCategoryRequestRepeater.repeat(function (item) {
                item.classList.remove('d-none');
                item.querySelector('.remove-btn').classList.remove('d-none');
                item.lastElementChild.innerHTML = '';
                requestFields(item.lastElementChild, workMethods, subCategoryRequestRepeater.length);

                item.firstElementChild.firstElementChild.innerHTML = `<input type="text" class="form-control" name="sub-category-requests[${subCategoryRequestRepeater.length}][name]" placeholder="আমার সাব-ক্যাটাগরির নাম">`;
            });
        });

        let subCategorySelect = document.getElementById('sub-categories');
        subCategorySelect.selectize.on('change', () => {
            subCategoryRateRepeater.removeAll();

            $(subCategorySelect).val().forEach((subCategoryId, subCategoryCount) => {
                if (!subCategoryId) return;
                subCategoryRateRepeater.repeat(function (item) {
                    item.classList.remove('d-none');
                    item.firstElementChild.innerHTML = subCategorySelect.querySelector(`[value='${subCategoryId}']`).innerHTML;
                    workMethods.forEach((workMethod, workMethodCount) => {
                        if (workMethod.id === 4) {
                            item.lastElementChild.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="work-method-${workMethod.id}-${subCategoryCount}" class="checkbox">${workMethod.name}
                                        <input type="checkbox" id="work-method-${workMethod.id}-${subCategoryCount}" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][checkbox]">
                                        <span></span>
                                    </label>
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][id]" value="${subCategoryId}">
                                    <input type="hidden" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][id]" value="${workMethod.id}">
                                </div>
                            </div>`;
                            return;
                        }

                        item.lastElementChild.innerHTML += `
                            <div class="row mt-2">
                                <div class="col-md-8">
                                    <label for="work-method-${workMethod.id}-${subCategoryCount}" class="checkbox">${workMethod.name}
                                        <input type="checkbox" id="work-method-${workMethod.id}-${subCategoryCount}" name="sub-category-rates[${subCategoryCount}][work-methods][${workMethodCount}][checkbox]">
                                        <span></span>
                                    </label>
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
import '../../../../sass/frontend/my-services/edit.scss';
import '../../../modules/selectize-option-loader-plugin';
import {Repeater} from "../../../modules/repeater";
import {FormChangeChecker} from "../../../modules/form-change-checker";
import {ImagePicker} from "../../../modules/image-picker";

document.addEventListener('DOMContentLoaded', function () {
    $('#division, #district, #thana, #union, #village').selectize({
        plugins: [
            'option-loader'
        ]
    });

    new ImagePicker(document.getElementsByClassName('file-picker'));

    let form = document.getElementById('update-form');
    let formIntegrity = new FormChangeChecker(form);

    document.getElementById('submit-btn').addEventListener('click', evt => {
        if (!formIntegrity.changed()) {
            evt.preventDefault();
            evt.stopPropagation();
            return false;
        }
    });

    /*********************** Sub-categories ***********************/
    (function () {
        let container = document.getElementById('sub-categories');
        fetch($(container).data('route')).then(response => response.json().then(subCategories => {

            let selected = $(container).find('.id-field').toArray().map(field => parseInt(field.value));
            let preSelected = [...selected];

            let repeater = new Repeater(container, function () {
                let count = this.count;
                let length = container.children.length;
                let options = '';

                subCategories.forEach(subCategory => {
                    if (!selected.includes(subCategory.id)) {
                        options += `
                        <option value="${subCategory.id}">
                            ${subCategory.name}
                        </option>
                        `;
                    }
                });

                let tr = `
                      <tr>
                                    <td> ${length + 1} </td>
                                    <td class="text-left">
                                        <select name="sub-categories[${count}][id]" class="form-control">
                                            <option value="">--- সাব-ক্যাটাগরি ---</option>
                                            ${options}
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control"
                                               name="sub-categories[${count}][rate]">
                                    </td>
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-sub-category">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
        `;

                let fragment = document.createElement('tbody');
                fragment.innerHTML = tr;
                let node = fragment.firstElementChild.cloneNode(true);
                $(node).find('.delete-sub-category').on('click', function () {
                    let tr = $(this).closest('tr');
                    let tbody = tr.closest('tbody');
                    tr.nextAll().hide(500, function () {
                        $(this).remove();
                    });
                    tr.hide(500, function () {
                        $(this).remove();
                        selected = [...tbody
                            .find('select')
                            .toArray()
                            .map(sl => parseInt(sl.value)), ...preSelected];
                    });
                });
                $(node).find('select').on('change', function () {
                    let select = $(this);
                    let value = parseInt(select.val());
                    if (value) {
                        selected.push(value);
                        select.closest('tr').nextAll().hide(500, function () {
                            $(this).remove();
                            selected = [...select.closest('tbody')
                                .find('select')
                                .toArray()
                                .map(sl => parseInt(sl.value)), ...preSelected];
                        });
                    }
                });
                return node;
            });

            document.getElementById('add-new-category').addEventListener('click', function () {
                let dontRepeat = $(container).find('select').toArray().some(select => {
                    return !select.value || select.value === '';
                }) || subCategories.length === selected.length;

                !dontRepeat && repeater.repeat();
            });
        }));

        document.querySelectorAll('.delete-sub-category').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm('আপনি কি নিশ্চিত যে আপনি এটি মুছে দিতে চান?')) {
                    $(button).closest('tr').hide(500, function () {
                        $(this).remove();
                    });
                }
            });
        });
    })();


    /******************** Sub-category requests *********************/
    (function () {
        document.querySelectorAll('.delete-sub-category').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm('আপনি কি নিশ্চিত যে আপনি এটি মুছে দিতে চান?')) {
                    $(button).closest('tr').hide(500, function () {
                        $(this).remove();
                    });
                }
            });
        });


        let container = document.getElementById('sub-category-requests');
        let repeater = new Repeater(container, function () {
            let count = this.count;
            let tr = `
                      <tr>
                                    <td> ২ </td>
                                    <td class="text-left">
                                        <input name="sub-category-requests[${count}][name]" type="text" class="form-control">
                                    </td>
                                    <td><input type="number" class="form-control" name="sub-category-requests[${count}][rate]">
                                    </td>
                                    <td>
                                        <span class="btn btn-outline-danger btn-sm delete-sub-category">
                                            <i class="fa fa-trash-o"></i> ডিলিট
                                        </span>
                                    </td>
                                </tr>
        `;

            let fragment = document.createElement('tbody');
            fragment.innerHTML = tr;
            let node = fragment.firstElementChild.cloneNode(true);
            $(node).find('.delete-sub-category').on('click', function () {
                $(this).closest('tr').hide(500, function () {
                    $(this).remove();
                });
            });
            return node;
        });
        document.getElementById('add-new-req').addEventListener('click', function () {
            repeater.repeat();
        });
    })();


    /******************** Additional pricing ********************/
    (function () {

        document.querySelectorAll('.delete-kaj').forEach(button => {
            button.addEventListener('click', () => {
                if (confirm('আপনি কি নিশ্চিত যে আপনি এটি মুছে দিতে চান?')) {
                    $(button).closest('.otirikto-kaj').hide(500, function () {
                        $(this).remove();
                    });
                }
            });
        });

        let container = document.getElementById('otirikto-kaj');
        let repeater = new Repeater(container, function () {
            let count = this.count;
            let kaj = `
                      <div class="row border rounded shadow-sm mt-2 position-relative otirikto-kaj" data-repeater-clone="true">
                                <div class="form-group  col-md-12 row mt-3">
                                    <label for="kaj-request-name-${count}" class="col-3 col-form-label">কাজের
                                        নামঃ </label>
                                    <div class="col-9">
                                        <input id="kaj-request-name-${count}" type="text" name="kaj-requests[${count}][name]" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group  col-md-12 row mt-2">
                                    <label for="kaj-request-info-${count}" class="col-3 col-form-label">তথ্যঃ </label>
                                    <div class="col-9">
                                    <textarea id="kaj-request-info-${count}" name="kaj-requests[${count}][info]" cols="50" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                                <i class="fa fa-trash-o delete-kaj text-danger" style="cursor: pointer"></i>
                      </div>
        `;

            let fragment = document.createElement('div');
            fragment.innerHTML = kaj;
            let node = fragment.firstElementChild.cloneNode(true);
            $(node).find('.delete-kaj').on('click', function () {
                $(this).closest('.otirikto-kaj').hide(500, function () {
                    $(this).remove();
                });
            });
            return node;
        });
        document.getElementById('add-new-kaj').addEventListener('click', function () {
            repeater.repeat();
        });
    })();
});
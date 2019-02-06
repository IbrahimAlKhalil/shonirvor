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


    /****************** Subcategories *************************/

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
                                    <td>
                                        <select name="sub-categories[${count}][id]" class="form-control">
                                            <option value="">--- সাব-ক্যাটাগরি ---</option>
                                            ${options}
                                        </select>
                                    </td>
                                                                                                                                                                                                                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-categories[${count}][work-methods][0][rate]" class="form-control">
                                                </div>
                                                                                                                                                                                                            </td><td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-categories[${count}][work-methods][1][rate]" class="form-control">
                                                </div>
                                                                                                                                                                                                            </td><td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-categories[${count}][work-methods][2][rate]" class="form-control">
                                                </div>
                                                                                                                                                                </td><td>
                                                <div class="d-flex justify-content-center align-content-center">
                                                    <label for="negotiable-${count}" class="mt-3 checkbox">
                                                        <input type="checkbox" id="negotiable-${count}" class="mt-2" name="sub-categories[${count}][work-methods][3][rate]" value="negotiable">
                                                        <span></span>
                                                    </label>
                                                </div>
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

            document.getElementById('add-new').addEventListener('click', function () {
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


    /**************************** Subcategory requests **********************/

    (function () {
        let container = document.getElementById('sub-category-requests');

        let repeater = new Repeater(container, function () {
            let count = this.count;
            let length = container.children.length + 1;

            let tr = `
            <tr>
                                <td>${length}</td>
                                <td>
                                    <input type="text" name="sub-category-requests[${count}][name]" class="form-control" placeholder="সাব-ক্যাটাগরির নাম" required>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" name="sub-category-requests[${count}][work-methods][0][rate]" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" name="sub-category-requests[${count}][work-methods][1][rate]" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" name="sub-category-requests[${count}][work-methods][2][rate]" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-content-center">
                                        <label for="requests-negotiable-${count}" class="mt-3 checkbox">
                                            <input type="checkbox" id="requests-negotiable-${count}" class="mt-2" name="sub-category-requests[${count}][work-methods][3][rate]" value="negotiable">
                                            <span></span>
                                        </label>
                                    </div>
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
            $('#update-form').removeAttr('novalidate');
            repeater.repeat();
        });
    })();

    /******************** Work images ********************/
    (function () {
        let container = document.getElementById('work-images');
        let repeater = new Repeater(container, function () {
            let count = this.count;
            let length = container.children.length;
            let tr = `
            <tr>
                <td>${length + 1}</td>
                <td>
                    <input type="file"
                           name="new-work-images[${count}][file]"
                           class="file-picker">
                </td>
                <td>
                    <textarea type="text" rows="4" name="new-work-images[${count}][description]" class="form-control"></textarea>
                </td>
                <td>
                    <span class="btn btn-outline-danger btn-sm delete-image">
                        <i class="fa fa-trash-o"></i> ডিলিট
                    </span>
                </td>
            </tr>
            `;
            let fragment = document.createElement('tbody');
            fragment.innerHTML = tr;
            let node = fragment.firstElementChild.cloneNode(true);
            $(node).find('.delete-image').on('click', function () {
                $(this).closest('tr').hide(500, function () {
                    $(this).remove();
                });
            });
            return node;
        });

        $('#add-new-image').on('click', function () {
            repeater.repeat().then(item => {
                new ImagePicker(item.querySelector('input'));
            });
        });

        $('.delete-image').on('click', function () {
            $(this).closest('tr').hide(500, function () {
                $(this).remove();
            });
        });

    })();
});
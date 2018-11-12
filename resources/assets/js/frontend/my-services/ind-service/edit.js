import '../../../../scss/frontend/my-services/edit.scss';
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
    window.bla = formIntegrity;

    document.getElementById('submit-btn').addEventListener('click', evt => {
        if (!formIntegrity.changed()) {
            console.log('Hello');
            evt.preventDefault();
            evt.stopPropagation();
            return false;
        }
    });

    document.querySelectorAll('.delete-sub-category').forEach(button => {
        button.addEventListener('click', () => {
            if (confirm('আপনি কি নিশ্চিত যে আপনি এটি মুছে দিতে চান?')) {
                $(button).closest('tr').hide(500, function () {
                    $(this).remove();
                });
            }
        });
    });

    let container = document.getElementById('sub-categories');
    let repeater = new Repeater(container, function () {
        let count = this.count;
        let tr = `
                      <tr data-repeater-clone="true">
                                    <td> 2 </td>
                                    <td>
                                        <input type="hidden" name="sub-categories[1][id]" value="32">
                                        <input type="text" name="sub-category-requests[${count}][name]" class="form-control">
                                    </td>
                                                                                                                                                                                                                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-category-requests[${count}][work-methods][1][rate]" class="form-control">
                                                </div>
                                                                                                                                                                                                            </td><td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-category-requests[${count}][work-methods][2][rate]" class="form-control">
                                                </div>
                                                                                                                                                                                                            </td><td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">৳</span>
                                                    </div>
                                                    <input type="text" name="sub-category-requests[${count}][work-methods][3][rate]" class="form-control">
                                                </div>
                                                                                                                                                                </td><td>
                                                <div class="d-flex justify-content-center align-content-center">
                                                    <label for="negotiable-${count}" class="mt-3 checkbox">
                                                        <input type="checkbox" id="negotiable-${count}" class="mt-2" name="sub-category-requests[${count}][work-methods][4][negotiable]">
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

    document.getElementById('add-new').addEventListener('click', function () {
        repeater.repeat();
    });
});
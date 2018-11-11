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
        let length = container.children.length;
        let tr = `
                      <tr data-repeater-clone="true">
                                <td> ${length + 2}</td>
                                <td><input type="text" class="form-control"></td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">৳</span>
                                        </div>
                                        <input type="text" class="form-control">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-content-center">
                                        <label for="no-thana" class="mt-3 checkbox">
                                            <input type="checkbox" id="no-thana" class="mt-2" name="no-thana">
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

    new ImagePicker(document.getElementsByClassName('file-picker'));
});
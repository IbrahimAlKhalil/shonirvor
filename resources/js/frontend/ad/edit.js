import '../../../sass/frontend/applications/ad/create.scss';
import {ImagePicker} from "../../modules/image-picker";
import {FormChangeChecker} from "../../modules/form-change-checker";

document.addEventListener('DOMContentLoaded', function () {
    new ImagePicker(document.getElementsByClassName('file-picker'));
    let form = new FormChangeChecker(document.getElementById('update-form'));
    $('[data-target="#acceptModal"]').on('click', function (evt) {
        if (!form.changed()) {
            evt.preventDefault();
            evt.stopPropagation();
        }
    });
});
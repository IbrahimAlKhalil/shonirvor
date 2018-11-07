import '../../../scss/frontend/applications/ad/create.scss';
import {ImagePicker} from "../../modules/image-picker";

document.addEventListener('DOMContentLoaded', function () {
    new ImagePicker(document.getElementsByClassName('file-picker'));
});
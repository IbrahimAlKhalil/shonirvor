import 'selectize/dist/css/selectize.default.css';
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
});
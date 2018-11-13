/***************/
/***** CSS *****/
/***************/

// Bootstrap CSS
import './../../../../../node_modules/bootstrap/dist/css/bootstrap.css';

// Common frontend css for all page
import '../../../../sass/frontend/components/_common.scss';
import '../../../../sass/frontend/applications/ad/create.scss';


/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';

import {ImagePicker} from "../../../modules/image-picker";

document.addEventListener('DOMContentLoaded', () => {
    let methodSelect = $("#create-method-select");

    methodSelect.on('change', function () {
        $("[id^='create-payment-number-']").addClass('d-none');
        $("#create-payment-number-" + methodSelect.val()).removeClass('d-none');
    });

    new ImagePicker(document.getElementsByClassName('file-picker'));
});
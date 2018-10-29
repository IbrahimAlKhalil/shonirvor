/***************/
/***** CSS *****/
/***************/

// Bootstrap CSS
import './../../../../../../node_modules/bootstrap/dist/css/bootstrap.css';

// Common frontend css for all page
import '../../../../scss/frontend/components/_common.scss';


/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';


document.addEventListener('DOMContentLoaded', () => {
    var methodSelect = $("#create-method-select");

    methodSelect.on('change', function (e) {
        $("[id^='create-payment-number-']").addClass('d-none');
        $("#create-payment-number-"+methodSelect.val()).removeClass('d-none');
    });
});
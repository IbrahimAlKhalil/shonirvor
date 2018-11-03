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
    var methodSelect = $("#method-select");

    methodSelect.on('change', function () {
        $("[id^='payment-number-']").addClass('d-none');
        $("#payment-number-" + methodSelect.val()).removeClass('d-none');
    });
});
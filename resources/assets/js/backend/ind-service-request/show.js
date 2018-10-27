/***** CSS *****/
import './../../../../../node_modules/bootstrap/dist/css/bootstrap.css'; // Bootstrap CSS
import './../../../scss/backend/components/_common.scss';

/***** JS *****/
import 'bootstrap'; // Bootstrap JS
import {OptionLoader} from "../../modules/option-loader";
import '../../modules/selectize-option-loader-plugin';

import '../../../../../node_modules/selectize/dist/css/selectize.default.css';
import '../../../scss/backend/ind-service-request/show.scss';

document.addEventListener('DOMContentLoaded', () => {
    $('#thana, #union, #village, #category').selectize({
        plugins: ['option-loader']
    });

    $('#package').selectize();

    document.querySelectorAll('.delete-sub-category').forEach(button => {
        button.addEventListener('click', () => {
            if (confirm('আপনি কি নিশ্চিত যে আপনি এটি মুছে দিতে চান?')) {
                $(button).closest('tr').hide(500, function () {
                    $(this).remove();
                });
            }
        });
    });
});
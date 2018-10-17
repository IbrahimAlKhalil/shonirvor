/***************/
/***** CSS *****/
/***************/

// Bootstrap CSS
import './../../../../node_modules/bootstrap/dist/css/bootstrap.css';

// Selectize JS
import './../../../../node_modules/selectize/dist/css/selectize.default.css';

// Common frontend css for all page
import '../../scss/frontend/components/_common.scss';

// Page lavel CSS
import '../../scss/frontend/filter.scss';



/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';

// Ajax Area & Category loader
import '../modules/option-loader';

// Selectize JS
import 'selectize';
import '../modules/selectize-option-loader-plugin'

// BS Star Rating
import './../../../../bower_components/bootstrap-star-rating/css/star-rating.css';
import './../../../../bower_components/bootstrap-star-rating/themes/krajee-fa/theme.css';
import './../../../../bower_components/bootstrap-star-rating/js/star-rating';

import {UrlPerser} from "../modules/url-param-perser";

document.addEventListener('DOMContentLoaded', () => {
    let methodSelect = $('#method + .selectize-control').parent();
    let priceSelect = $('#price + .selectize-control').parent();
    let url = new UrlPerser(location.search);

    if ( ! url.filled('method') ) {
        priceSelect.hide();

        if ( ! url.filled('sub-category') ) {
            methodSelect.hide();
        }
    }

    document.getElementById('subCategory').selectize.on('change', value => {

        if (!!value) {

            $.ajax({
                url: 'api/sub-categories/'+value,
                success: function(result) {

                    if ( result.category.service_type_id == 1) {
                        methodSelect.show();
                    }

                    priceSelect.show();
                }
            });

            return;

        }

        methodSelect.hide();
        priceSelect.hide();
        $("#method")[0].selectize.clear();
        $("#price")[0].selectize.clear();

    });
});
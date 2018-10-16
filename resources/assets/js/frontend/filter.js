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
    let methodSelect = $('#method + .selectize-control');
    let priceSelect = $('#price + .selectize-control');
    let url = new UrlPerser(location.search);

    if (!url.has('sub-category')) {
        methodSelect.addClass('d-none');
    }

    if(!url.has('method') || !url.has('sub-category')) {
        priceSelect.addClass('d-none');
    }

    document.getElementById('subCategory').selectize.on('change', value => {
        priceSelect.addClass('d-none');

        if (!!value) {
            methodSelect.removeClass('d-none');
            return;
        }

        methodSelect.addClass('d-none');
    });

    document.getElementById('method').selectize.on('change', value => {
        if (!!value) {
            priceSelect.removeClass('d-none');
            return;
        }

        priceSelect.addClass('d-none');
    });
});
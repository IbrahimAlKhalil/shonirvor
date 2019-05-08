/***************/
/***** CSS *****/
/***************/

// Common frontend css for all page
import '../../sass/frontend/components/_common.scss';

// Page lavel CSS
import './../../sass/frontend/ind-service.scss';


/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';

// Ajax Area & Category loader
import './../../js/modules/option-loader';

// BS Star Rating
import './../../../bower_components/bootstrap-star-rating/css/star-rating.css';
import './../../../bower_components/bootstrap-star-rating/themes/krajee-fa/theme.css';
import './../../../bower_components/bootstrap-star-rating/js/star-rating';

import protocolCheck from 'custom-protocol-detection-blockstack'

$(document).ready(() => {

    $(".facebook-link").click(function (event) {
        event.preventDefault();

        const anchor = $(this);
        const url = anchor.attr('href');
        const regEx = /(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[?\w\-]*\/)?(?:profile.php\?id=(?=\d.*))?([\w\-]*)?/;
        const id = url.match(regEx)[1];

        const isUserName = id.split('').some(letter => {
            return letter.match(/\w/);
        });

        const fbLink = isUserName ? url : `fb://profile/${id}`;

        protocolCheck(fbLink,
            () => {
                window.open(url, '_blank');
            },
            () => {

            },
            () => {
                window.open(url, '_blank');
            });
    });
});


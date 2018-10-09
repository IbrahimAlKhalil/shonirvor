/***************/
/***** CSS *****/
/***************/

// Bootstrap CSS
import './../../../../../node_modules/bootstrap/dist/css/bootstrap.css';

// Common frontend css for all page
import '../../../scss/frontend/components/_common.scss';

// Page lavel CSS
import './../../../scss/frontend/ind-service/show.scss';



/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';

// Ajax Area & Category loader
import './../../../js/modules/option-loader';

// BS Star Rating
import './../../../../../bower_components/bootstrap-star-rating/css/star-rating.css';
import './../../../../../bower_components/bootstrap-star-rating/themes/krajee-fa/theme.css';
import './../../../../../bower_components/bootstrap-star-rating/js/star-rating';



/*************************/
/***** Page Level Js *****/
/*************************/

$(document).ready(function () {

    // Star Rating
    $('#storeStar').rating({
        step: 1,
        size: 'sm',
        showClear: false,
        starCaptions: {
            1: 'এক তারা',
            2: 'দুই তারা',
            3: 'তিন তারা',
            4: 'চার তারা',
            5: 'পাঁচ তারা'
        },
        clearCaption: 'কোন তারা নেই',
        filledStar: '<i class="fa fa-star"></i>',
        emptyStar: '<i class="fa fa-star-o"></i>',
        clearButton: '<i class="fa fa-lg fa-minus-circle"></i>'
    });
    $('[id^="showStar"]').rating({
        step: 1,
        size: 'xm',
        filledStar: '<i class="fa fa-star"></i>',
        emptyStar: '<i class="fa fa-star-o"></i>',
        showClear: false,
        showCaption: false,
        showCaptionAsTitle: false,
        displayOnly: true
    });

});
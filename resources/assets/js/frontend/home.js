/***************/
/***** CSS *****/
/***************/

// Bootstrap CSS
import './../../../../node_modules/bootstrap/dist/css/bootstrap.css';

// Common frontend css for all page
import '../../scss/frontend/components/_common.scss';

// Page lavel CSS
import '../../scss/frontend/home.scss';



/**************/
/***** JS *****/
/**************/

// Bootstrap JS
import 'bootstrap';

// Ajax Area & Category loader
import '../modules/option-loader';

// Search
import '../modules/search';

// BS Star Rating
import './../../../../bower_components/bootstrap-star-rating/css/star-rating.css';
import './../../../../bower_components/bootstrap-star-rating/themes/krajee-fa/theme.css';
import './../../../../bower_components/bootstrap-star-rating/js/star-rating';



/*************************/
/***** Page Level Js *****/
/*************************/

$(document).ready(function () {

    // Star Rating
    $('[id^="topIndStar"], [id^="topOrgStar"]').rating({
        step: 0.1,
        size: 'xm',
        displayOnly: true,
        filledStar: '<i class="fa fa-star"></i>',
        emptyStar: '<i class="fa fa-star-o"></i>',
        showClear: false,
        showCaptionAsTitle: false,
        clearCaptionClass: 'd-none',
        starCaptions: {
            1: '১',
            1.1: '১.১',
            1.2: '১.২',
            1.3: '১.৩',
            1.4: '১.৪',
            1.5: '১.৫',
            1.6: '১.৬',
            1.7: '১.৭',
            1.8: '১.৮',
            1.9: '১.৯',
            2: '২',
            2.1: '২.১',
            2.2: '২.২',
            2.3: '২.৩',
            2.4: '২.৪',
            2.5: '২.৫',
            2.6: '২.৬',
            2.7: '২.৭',
            2.8: '২.৮',
            2.9: '২.৯',
            3: '৩',
            3.1: '৩.১',
            3.2: '৩.২',
            3.3: '৩.৩',
            3.4: '৩.৪',
            3.5: '৩.৫',
            3.6: '৩.৬',
            3.7: '৩.৭',
            3.8: '৩.৮',
            3.9: '৩.৯',
            4: '৪',
            4.1: '৪.১',
            4.2: '৪.২',
            4.3: '৪.৩',
            4.4: '৪.৪',
            4.5: '৪.৫',
            4.6: '৪.৬',
            4.7: '৪.৭',
            4.8: '৪.৮',
            4.9: '৪.৯',
            5: '৫'
        },
        starCaptionClasses: {
            1: 'badge badge-pill badge-danger',
            1.1: 'badge badge-pill badge-danger',
            1.2: 'badge badge-pill badge-danger',
            1.3: 'badge badge-pill badge-danger',
            1.4: 'badge badge-pill badge-danger',
            1.5: 'badge badge-pill badge-warning',
            1.6: 'badge badge-pill badge-warning',
            1.7: 'badge badge-pill badge-warning',
            1.8: 'badge badge-pill badge-warning',
            1.9: 'badge badge-pill badge-warning',
            2: 'badge badge-pill badge-warning',
            2.1: 'badge badge-pill badge-warning',
            2.2: 'badge badge-pill badge-warning',
            2.3: 'badge badge-pill badge-warning',
            2.4: 'badge badge-pill badge-warning',
            2.5: 'badge badge-pill badge-info',
            2.6: 'badge badge-pill badge-info',
            2.7: 'badge badge-pill badge-info',
            2.8: 'badge badge-pill badge-info',
            2.9: 'badge badge-pill badge-info',
            3: 'badge badge-pill badge-info',
            3.1: 'badge badge-pill badge-info',
            3.2: 'badge badge-pill badge-info',
            3.3: 'badge badge-pill badge-info',
            3.4: 'badge badge-pill badge-info',
            3.5: 'badge badge-pill badge-primary',
            3.6: 'badge badge-pill badge-primary',
            3.7: 'badge badge-pill badge-primary',
            3.8: 'badge badge-pill badge-primary',
            3.9: 'badge badge-pill badge-primary',
            4: 'badge badge-pill badge-primary',
            4.1: 'badge badge-pill badge-primary',
            4.2: 'badge badge-pill badge-primary',
            4.3: 'badge badge-pill badge-primary',
            4.4: 'badge badge-pill badge-primary',
            4.5: 'badge badge-pill badge-success',
            4.6: 'badge badge-pill badge-success',
            4.7: 'badge badge-pill badge-success',
            4.8: 'badge badge-pill badge-success',
            4.9: 'badge badge-pill badge-success',
            5: 'badge badge-pill badge-success'
        }
    });

});
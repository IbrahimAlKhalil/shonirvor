// jQuery
import jquery from 'jquery';
window.$ = window.jQuery = jquery;

// BS Star Rating
import './../../../../../../bower_components/bootstrap-star-rating/css/star-rating.css';
import './../../../../../../bower_components/bootstrap-star-rating/themes/krajee-fa/theme.css';
import './../../../../../../bower_components/bootstrap-star-rating/js/star-rating';

$(document).ready(function () {
    $('#storeStar').rating({
        step: 1,
        size: 'sm',
        starCaptions: {
            1: 'এক তারা',
            2: 'দুই তারা',
            3: 'তিন তারা',
            4: 'চার তারা',
            5: 'পাঁচ তারা'
        },
        clearButtonTitle: 'মুছে ফেলুন',
        clearCaption: 'কোন তারা নেই',
        filledStar: '<i class="fa fa-star"></i>',
        emptyStar: '<i class="fa fa-star-o"></i>',
        clearButton: '<i class="fa fa-lg fa-minus-circle"></i>',
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
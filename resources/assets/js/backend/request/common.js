$(document).ready(function () {
    $('[form="approve-form"], [form="reject-form"]').on('click', function (evt) {
        if (!confirm('আপনি কি শিওর?')) {
            evt.preventDefault();
            return false;
        }

        $(this)[0].form.submit();
    });
});
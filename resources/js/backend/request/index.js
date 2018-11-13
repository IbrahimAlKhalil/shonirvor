document.addEventListener('DOMContentLoaded', function () {
    let acceptModal = $('#acceptModal');
    let deleteModal = $('#deleteModal');
    let acceptForm = $('#approve-form');
    let deleteForm = $('#delete-form');

    $('.accept-btn').on('click', function () {
        acceptForm.attr('action', `${location.href}/${$(this).closest('tr').data('edit-id')}`);
        acceptModal.modal().show();
    });

    $('.delete-btn').on('click', function () {
        deleteForm.attr('action', `${location.href}/${$(this).closest('tr').data('edit-id')}`);
        deleteModal.modal().show();
    });
});
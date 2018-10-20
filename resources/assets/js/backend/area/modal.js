// Selectize JS
import '../../modules/selectize-option-loader-plugin';
import '../../../../../node_modules/selectize/dist/css/selectize.default.css';

document.addEventListener('DOMContentLoaded', () => {
    let selects = document.querySelectorAll('#division, #district, #thana, #union, #village');

    $(selects).selectize({
        plugins: ['option-loader']
    });


    let editModal = document.getElementById('edit-modal');
    let editForm = document.getElementById('edit-form');
    let editFormDataAction = editForm.getAttribute('data-action');
    let editFormAction = editFormDataAction.slice(0, editFormDataAction.lastIndexOf('/'));
    let editModalLabelElement = document.getElementById('edit-modal-label');
    let editSuffix = editModalLabelElement.getAttribute('data-suffix');
    let editButtons = document.querySelectorAll('.edit-btn');
    let nameInput = document.getElementById('bn-name');
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            let name = button.parentElement.previousElementSibling.innerText;
            editModalLabelElement.innerHTML = `${name} ${editSuffix}`;
            editForm.action = `${editFormAction}/${button.parentElement.getAttribute('data-item-id')}`;
            nameInput.value = name;
            selects.forEach(select => {
                select.selectize.setValue(select.getAttribute('data-default-value'), true);
            });
            $(editModal).modal('show');
        });
    });

    let deleteModal = document.getElementById('delete-modal');
    let deleteForm = document.getElementById('delete-form');
    let deleteFormDataAction = deleteForm.getAttribute('data-action');
    let deleteFormAction = deleteFormDataAction.slice(0, deleteFormDataAction.lastIndexOf('/'));
    let deleteModalLabelElement = document.getElementById('delete-modal-label');
    let deleteSuffix = deleteModalLabelElement.getAttribute('data-suffix');
    let deletePrefix = deleteModalLabelElement.getAttribute('data-prefix');
    let deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            let name = button.parentElement.previousElementSibling.innerText;
            deleteModalLabelElement.innerHTML = `${deletePrefix} ${name} ${deleteSuffix}`;
            deleteForm.action = `${deleteFormAction}/${button.parentElement.getAttribute('data-item-id')}`;
            $(deleteModal).modal('show');
        });
    });

});
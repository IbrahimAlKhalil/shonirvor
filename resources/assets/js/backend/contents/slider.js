/***** CSS *****/
import './../../../../../node_modules/bootstrap/dist/css/bootstrap.css'; // Bootstrap CSS
import './../../../scss/backend/components/_common.scss';
import '../../../scss/backend/contents/slider.scss';

/***** JS *****/
import $ from 'jquery';
import 'bootstrap'; // Bootstrap JS
import Sortable from 'sortablejs';


/*function handleSorting(event) {
    event.item.querySelector('.image-order').value = event.newIndex + 1;
    event.srcElement
        .children[event.oldIndex]
        .querySelector('.image-order')
        .value = event.oldIndex + 1;
}*/

function handleChangeBtnClick(element) {
    $(element).next().click();
}

function shoImage(element) {
    element.parentElement
        .parentElement
        .previousElementSibling
        .firstElementChild.src = URL.createObjectURL(element.files[0]);
}

document.addEventListener('DOMContentLoaded', () => {
    let noImage = `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUoAAACqBAMAAAA5NBsAAAAAHlBMVEX///+5ubnKysrT09PFxcXb29v4+Pjm5ubx8fG/v79jhCz7AAACiUlEQVR42u3bS2sTURjG8ZfUmZztkws2u7TCAXcGsXRpRFx3pN52gxa1u4qXxl0UN1mOC/XjGuPMScZDWnKwJ+/Q5/cJ/jxvAiHJCBEREREREREREREREZFe5v3+pm5NJLJ0hAAHEtc3hOhNJSZTIMgviekEYXq5RJQh0JHEkxYI9EPiaf853pNNHQO4KfG0g1ZJi7iVOwCGsrE9oCvrsZKVrFRZ+XaSq680x8D3p8or0xeYGxzprvyKhW6uudJ97BhqrnyFUl9z5TtUpnor0zEqQ72VbTi7eit34PT1Vt6A0xXPa32VHflXup/rqDy9qPIlrI7Kiy6e7qGTq6hswen7UwJWRWUCZ9efEvMxNVSaApVH/pRzVkOlZKic+VMuxtRQeYJSx59ywWqoNO7g/pTlmAoq5UMZM/WmLFkNlebvaAf+lG5MBZXSvg0MDsWb0rEaKiX9+OyT+FOujKmh8tKfBqzOSlMA3phbqrwv68xQZzVWuim9MSNXtvxKf8olq2/LasramOoqZ/BZJRfPa1P6Y6rYMrG1KT1WRWXWyWtT+mMquHgC2NqUHqtgy6xcy03p6Wy/MnFrzbBOvvWLZ9VLz4w1Vda3TNxLbwa9lVn1PjaFqsraxROU7AyqKmtbZij1xnorEyzpqly9eKa2cmXLBE2ozPRWLi+eQG/lcsusCZUJFFe6i2eaK6stEzSh8ovqylYjKpuxZTMqefHrVsmLX7fKZl38zfnlJgvn2/8GRun/L1uNqGzGls2o5MX/nyTogSJTxH/aY3B3E/e28EyKQROe75ERAt2RiE4RZpBLRO0CQX5KVJ/DpjyTqMwIAQ4lMvP44aYePBciIiIiIiIiIiIiIrpyvwGPXU9VynS3IQAAAABJRU5ErkJggg==`;
    let container = document.getElementById("image-list");
    let sort = Sortable.create(container, {
        animation: 150,
        draggable: ".list-group-item",
        handle: '.list-group-item',
        sort: true/*,
        onUpdate: handleSorting*/
    });

    window.sort = sort;
    window.Sortable = Sortable;

    let deleteForm = document.getElementById('delete-form');
    document.querySelectorAll('.delete-image').forEach(element => element.addEventListener('click', event => {
        event.preventDefault();
        event.stopPropagation();
        deleteForm.action = `${deleteForm.getAttribute('data-action')}/${element.getAttribute('data-content-id')}`;
        deleteForm.submit();
    }));

    document.querySelectorAll('.change-image').forEach(element => element.addEventListener('click', () => {
        handleChangeBtnClick(element);
    }));

    document.querySelectorAll('.image-field').forEach(element => element.addEventListener('change', () => {
        shoImage(element);
    }));

    document.getElementById('add-new').addEventListener('click', () => {
        let newList = Sortable.utils.clone(container.lastElementChild);
        let order = container.children.length;
        let orderInput = newList.querySelector('.image-order');
        let linkInput = newList.querySelector('.action-link');

        newList.querySelector('.slider-image').src = noImage;

        orderInput.name = `images[image-${order}][order]`;
        orderInput.value = order;

        linkInput.name = `images[image-${order}][link]`;
        linkInput.value = '';

        newList.querySelector('.image-field').name = `sliders[image-${order}]`;

        container.appendChild(newList);

        newList.querySelector('.change-image').addEventListener('click', event => {
            handleChangeBtnClick(event.target);
        });

        newList.querySelector('.image-field').addEventListener('change', event => {
            shoImage(event.target);
        });

        newList.querySelector('.delete-image').addEventListener('click', () => {
            $(newList).fadeOut(400, () => {
                $(newList).remove();
                newList = null;
            });
        });
    });
});
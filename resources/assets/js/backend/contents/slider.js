/***** CSS *****/
import './../../../../../node_modules/bootstrap/dist/css/bootstrap.css'; // Bootstrap CSS
import './../../../scss/backend/components/_common.scss';
import '../../../scss/backend/contents/slider.scss';

/***** JS *****/
import 'bootstrap'; // Bootstrap JS
import Sortable from 'sortablejs';
import {Repeater} from "../../modules/repeater";

function handleChangeBtnClick(element) {
    $(element).next().click();
}

function showImage(element) {
    element.parentElement
        .parentElement
        .previousElementSibling
        .firstElementChild.src = URL.createObjectURL(element.files[0]);
}

document.addEventListener('DOMContentLoaded', () => {
    let noImage = `data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAUoAAACqBAMAAAA5NBsAAAAAHlBMVEX///+5ubnKysrT09PFxcXb29v4+Pjm5ubx8fG/v79jhCz7AAACiUlEQVR42u3bS2sTURjG8ZfUmZztkws2u7TCAXcGsXRpRFx3pN52gxa1u4qXxl0UN1mOC/XjGuPMScZDWnKwJ+/Q5/cJ/jxvAiHJCBEREREREREREREREZFe5v3+pm5NJLJ0hAAHEtc3hOhNJSZTIMgviekEYXq5RJQh0JHEkxYI9EPiaf853pNNHQO4KfG0g1ZJi7iVOwCGsrE9oCvrsZKVrFRZ+XaSq680x8D3p8or0xeYGxzprvyKhW6uudJ97BhqrnyFUl9z5TtUpnor0zEqQ72VbTi7eit34PT1Vt6A0xXPa32VHflXup/rqDy9qPIlrI7Kiy6e7qGTq6hswen7UwJWRWUCZ9efEvMxNVSaApVH/pRzVkOlZKic+VMuxtRQeYJSx59ywWqoNO7g/pTlmAoq5UMZM/WmLFkNlebvaAf+lG5MBZXSvg0MDsWb0rEaKiX9+OyT+FOujKmh8tKfBqzOSlMA3phbqrwv68xQZzVWuim9MSNXtvxKf8olq2/LasramOoqZ/BZJRfPa1P6Y6rYMrG1KT1WRWXWyWtT+mMquHgC2NqUHqtgy6xcy03p6Wy/MnFrzbBOvvWLZ9VLz4w1Vda3TNxLbwa9lVn1PjaFqsraxROU7AyqKmtbZij1xnorEyzpqly9eKa2cmXLBE2ozPRWLi+eQG/lcsusCZUJFFe6i2eaK6stEzSh8ovqylYjKpuxZTMqefHrVsmLX7fKZl38zfnlJgvn2/8GRun/L1uNqGzGls2o5MX/nyTogSJTxH/aY3B3E/e28EyKQROe75ERAt2RiE4RZpBLRO0CQX5KVJ/DpjyTqMwIAQ4lMvP44aYePBciIiIiIiIiIiIiIrpyvwGPXU9VynS3IQAAAABJRU5ErkJggg==`;
    let container = document.getElementById("image-list");
    Sortable.create(container, {
        animation: 150,
        draggable: ".list-group-item",
        handle: '.list-group-item',
        sort: true/*,
        onUpdate: handleSorting*/
    });

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
        showImage(element);
    }));


    let repeater = new Repeater(container, function () {
        let order = container.children.length;
        let li = `<li class="list-group-item mb-2 shadow-sm" data-repeater-clone="true">
                      <div class="row">
                          <div class="col-md-9">
                              <div class="row">
                                  <div class="col-md-3">
                                      <img src="${noImage}" class="rounded img-fluid slider-image">
                                  </div>

                                  <div class="col-md-6">
                                      <input name="images[image-${order}][link]" type="url" class="form-control w-100 mb-2 action-link" placeholder="লিঙ্ক" value="">
                                      <input type="hidden" name="images[image-${order}][prev-image]" value="default/home-slider/2.jpg">
                                      <input type="hidden" name="images[image-${order}][id]" value="10">
                                      <div>
                                          <button class="btn btn-primary change-image" type="button">ছবি
                                              পরিবর্তন
                                              করুন
                                          </button>
                                          <input name="sliders[image-${order}]" type="file" class="form-control-file w-100 image-field" accept="image/*">

                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="col-md-3">
                              <a class="fa fa-trash float-right text-danger delete-image remove-btn" href="#" data-content-id="10"></a>
                          </div>
                      </div>
                  </li>`;
        let fragment = document.createElement('ul');
        fragment.innerHTML = li;
        let node = fragment.firstElementChild.cloneNode(true);

        node.querySelector('.change-image').addEventListener('click', event => {
            handleChangeBtnClick(event.target);
        });

        node.querySelector('.image-field').addEventListener('change', event => {
            showImage(event.target);
        });

        return node;
    });

    document.getElementById('add-new').addEventListener('click', () => {
        repeater.repeat();
    });
});
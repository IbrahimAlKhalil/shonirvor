import {Repeater} from '../../../modules/repeater';
import {OptionLoader} from "../../../modules/option-loader";

document.addEventListener('DOMContentLoaded', () => {
    function removeAllAndRepeat() {
        repeater.removeAll();
        repeater.repeat();
    }

    function removeAfterAndRepeat() {
        while (this.parentElement.nextElementSibling) {
            this.parentElement.nextElementSibling.remove();
        }
        repeater.repeat();
    }

    let repeater = new Repeater(document.getElementById('sub-category-parent'), {
        process: (item) => {
            let select = item.firstElementChild;
            let input = item.lastElementChild;
            select.removeAttribute('id');
            select.name = `sub-categories[${repeater.length}][id]`;
            input.name = `sub-categories[${repeater.length}][rate]`;

            select.addEventListener('change', removeAfterAndRepeat);
        }
    });

    window.repeater = repeater;

    document.getElementById('sub-categories').addEventListener('change', removeAllAndRepeat);

    let subCategoryLoader = new OptionLoader(document.getElementById('category'), {
        callback: () => {
            repeater.removeAll();
        },
        emptyCallback: () => {
            console.log('hello');
        }
    });
});


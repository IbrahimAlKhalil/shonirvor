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
        process: (item, prev, instance) => {
            let select = item.firstElementChild;
            let input = item.lastElementChild;
            let clones = [instance.original, ...instance.clones];
            select.removeAttribute('id');
            select.name = `sub-categories[${repeater.length}][id]`;
            input.name = `sub-categories[${repeater.length}][rate]`;

            clones.forEach(clone => {
                select.querySelector(`[value="${clone.firstElementChild.value}"]`).remove();
            });


            select.addEventListener('change', removeAfterAndRepeat);
        }
    });

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


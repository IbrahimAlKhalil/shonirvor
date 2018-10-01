import {Repeater} from '../../modules/repeater';
import {OptionLoader} from "../../modules/option-loader";

document.addEventListener('DOMContentLoaded', () => {
    let subCategoryLoader = new OptionLoader(document.getElementById('category'), (target, data, select) => {
        console.log(target, data, select);
    });
});


import {querySelectorAll} from "./scoped-query-selector";

export class Repeater {
    constructor(container, markup) {
        this.container = container;
        this.markup = markup;
    }

    repeat(params) {
        let instance = this;
        return new Promise(resolve => {
            let clone = instance.markup.apply(instance, params);
            let insertBefore = instance.container.querySelector('.repeater-insert-before');
            clone.setAttribute('data-repeater-clone', 'true');
            instance.container.insertBefore(clone, insertBefore);
            resolve(clone);
        });
    }

    removeAll() {
        this.clones.forEach(clone => {
            clone.remove();
        });
    }

    get clones() {
        return [...querySelectorAll(this.container, '> [data-repeater-clone]')];
    }

    get length() {
        return this.clones.length + 1;
    }
}
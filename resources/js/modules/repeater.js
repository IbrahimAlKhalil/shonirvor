import {querySelectorAll} from "./scoped-query-selector";

export class Repeater {
    constructor(container, markupCallback) {
        this.container = container;
        this.markupCallback = markupCallback;
        this.count = querySelectorAll(container, '> [data-repeater-clone]').length + 1;
    }

    repeat(params) {
        return new Promise(resolve => {
            let clone = this.markupCallback.apply(this, params);
            let insertBefore = this.container.querySelector('.repeater-insert-before');
            clone.setAttribute('data-repeater-clone', 'true');
            this.container.insertBefore(clone, insertBefore);
            this.count++;
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
        return this.clones.length;
    }
}
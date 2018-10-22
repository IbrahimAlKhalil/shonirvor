import {querySelectorAll} from "./scoped-query-selector";

export class Repeater {
    constructor(container, options) {
        this.container = container;
        this.original = container.querySelector('.repeater-clone') || container.lastElementChild;
        let lastCloned = querySelectorAll(container, '> [data-cloned]');
        this.lastCloned = lastCloned.length ? lastCloned[lastCloned.length - 1] : this.original;
        this.options = {...options};

        this.clones.forEach(clone => {
            let removeBtn = clone.querySelector('.remove-btn');
            removeBtn && removeBtn.addEventListener('click', event => {
                event.preventDefault();
                this.remove(clone);
            });
        });

    }

    repeat(process) {
        let instance = this;
        return new Promise((resolve => {
            let newItem = this.original.cloneNode(true);
            let removeBtn = newItem.querySelector('.remove-btn');
            if (typeof this.options.process === 'function') {
                this.options.process.call(this, newItem, this.lastCloned, instance);
            }

            if (typeof process === 'function') {
                process.call(this, newItem, this.lastCloned, instance);
            }

            this.container.insertBefore(newItem, this.lastCloned.nextElementSibling);
            this.lastCloned.classList.remove('repeater-clone');
            newItem.previousClone = this.lastCloned;
            this.lastCloned = newItem;
            this.clones.push(newItem);
            newItem.setAttribute('data-cloned', true);
            removeBtn && removeBtn.addEventListener('click', event => {
                event.preventDefault();
                instance.remove(newItem);
            });

            resolve(newItem);
        }));
    }

    remove(element) {
        let instance = this;
        $(element).fadeOut(400, () => {
            instance.lastCloned = element.previousClone;
            $(element).remove();
        });
    }

    removeAll() {
        this.clones.forEach(clone => {
            clone.remove();
        });
        this.lastCloned = this.original;
    }

    get clones() {
        return [...querySelectorAll(this.container, '> [data-cloned]')];
    }

    get length() {
        return this.clones.length + 1;
    }
}
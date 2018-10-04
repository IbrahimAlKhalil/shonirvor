import $ from "jquery";

export class Repeater {
    constructor(container, options) {
        this.container = container;
        this.lastCloned = container.querySelector('.repeater-clone') || container.lastElementChild;
        this.original = this.lastCloned;
        this.options = {...options};
    }

    repeat(process) {
        let instance = this;
        return new Promise((resolve => {
            let newItem = this.original.cloneNode(true);
            let removeBtn = newItem.querySelector('.remove-btn');
            if (typeof this.options.process === 'function') {
                this.options.process.call(this, newItem, this.lastCloned);
            }

            if (typeof process === 'function') {
                process.call(this, newItem, this.lastCloned);
            }

            this.container.insertBefore(newItem, this.lastCloned.nextElementSibling);
            this.lastCloned.classList.remove('repeater-clone');
            newItem.previouseClone = this.lastCloned;
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
            instance.lastCloned = element.previouseClone;
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
        return [...this.container.querySelectorAll('[data-cloned]')];
    }

    get length() {
        return this.clones.length + 1;
    }
}
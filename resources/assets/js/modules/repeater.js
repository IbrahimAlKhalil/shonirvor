import $ from "jquery";

export class Repeater {
    constructor(container, options) {
        this.container = container;
        this.lastCloned = container.querySelector('.repeater-clone') || container.lastElementChild;
        this.original = this.lastCloned;
        this.options = {...options};
    }

    repeat() {
        let instance = this;
        return new Promise((resolve => {
            let newItem = this.original.cloneNode(true);
            let removeBtn = newItem.querySelector('.remove-btn');
            if (typeof this.options.process === 'function') {
                this.options.process.call(window, newItem, this.lastCloned);
            }

            this.container.insertBefore(newItem, this.lastCloned.nextElementSibling);
            this.lastCloned.classList.remove('repeater-clone');
            this.lastCloned = newItem;
            this.clones.push(newItem);
            newItem.setAttribute('data-cloned', true);
            removeBtn && removeBtn.addEventListener('click', () => {
                instance.remove(newItem);
            });

            resolve(newItem);
        }));
    }

    remove(element) {
        $(element).fadeOut(400, () => {
            $(element).remove();
            element = null;
            this.clones.forEach((clone, index) => {
                if (clone === element) {
                    this.clones.splice(index, 1);
                }
            });
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
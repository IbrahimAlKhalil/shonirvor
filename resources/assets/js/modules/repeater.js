import $ from "jquery";

export class Repeater {
    constructor(container, options) {
        this.container = container;
        this.options = {...options};
    }

    repeat() {
        let instance = this;
        return new Promise((resolve => {
            let newItem = this.container.lastElementChild.cloneNode(true);
            let removeBtn = newItem.querySelector('.remove-btn');
            if (typeof this.options.process === 'function') {
                this.options.process.call(window, newItem);
            }

            this.container.appendChild(newItem);
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
        });
    }
}
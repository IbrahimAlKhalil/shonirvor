import '../../scss/frontend/components/_image-picker.scss';

export class ImagePicker {
    constructor(inputElements) {
        this.pickers = [];
        let inputs = [inputElements];
        let pickerClassList = ['image-picker'];
        let controlOptions = {
            classList: ['image-picker-control'],
            image: {
                classList: ['image-picker-image']
            },
            button: {
                classList: [
                    'image-picker-button',
                    'shadow-sm'
                ],
                icon: '<i class="fa fa-plus" aria-hidden="true"></i>'
            }
        };

        if (typeof inputElements.length === 'number') {
            inputs = [...inputElements];
        }

        inputs.forEach(input => {
            let container = input.parentElement;
            let nextSibling = input.nextElementSibling;
            let picker = new Picker({
                classList: pickerClassList,
                control: {
                    input: document.adoptNode(input),
                    ...controlOptions
                }
            });

            this.pickers.push(picker);
            container.insertBefore(picker.element, nextSibling);
        });
    }
}

class Picker {
    constructor(options) {
        this.controlOptions = options.control;
        this.element = document.createElement('div');
        this.addControl();
        addClasses(this.element, options.classList);
    }

    addControl() {
        let control = new Control(this.controlOptions);
        this.element.appendChild(control.element);
        control.input.addEventListener('change', () => {
            control.preview();
            control.hideButton();
            let cross = document.createElement('span');
            cross.classList.add('cross', 'fa-times', 'fa');
            cross.addEventListener('click', () => {
                this.removeControl(control);
                if (!this.controlOptions.input.multiple) {
                    this.addControl();
                }
            });
            control.imageDiv.appendChild(cross);

            if (this.controlOptions.input.multiple) {
                this.addControl();
            }
        });
    }

    removeControl(control) {
        control.element.parentElement.removeChild(control.element);
        control = null;
    }
}

class Control {
    constructor(options) {
        let id = `${options.input.id}-${Date.now()}`;
        this.options = options;
        this.element = document.createElement('div');
        this.button = document.createElement('label');
        this.button.innerHTML = options.button.icon;
        this.input = options.input.cloneNode(true);
        this.button.for = id;
        this.input.id = id;
        this.input.style.display = 'none';
        this.input.removeAttribute('multiple');
        addClasses(this.button, options.button.classList);
        addClasses(this.element, options.classList);
        this.button.appendChild(this.input);
        this.element.appendChild(this.button);

        if (this.input.hasAttribute('data-image')) {
            this.preview();
            this.hideButton();
            let cross = document.createElement('span');
            cross.classList.add('cross', 'fa', 'fa-times');
            cross.addEventListener('click', () => {
                this.imageDiv.parentElement.removeChild(this.imageDiv);
                this.button.style.display = 'flex';
                cross = null;
            });
            this.imageDiv.appendChild(cross);
        }
    }

    preview() {
        this.imageDiv = document.createElement('div');
        let url = this.input.files[0] ? URL.createObjectURL(this.input.files[0]) : this.input.getAttribute('data-image');
        this.imageDiv.style.backgroundImage = `url(${url})`;
        addClasses(this.imageDiv, this.options.image.classList);
        this.element.insertBefore(this.imageDiv, this.button);
    }

    hideButton() {
        this.button.style.display = 'none';
    }
}


// Helpers
function addClasses(element, classList) {
    classList.forEach(_class => element.classList.add(_class));
}
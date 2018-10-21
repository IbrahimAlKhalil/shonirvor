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
                classList: ['image-picker-button'],
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
            cross.innerHTML = 'x';
            cross.classList.add('cross');
            cross.addEventListener('click', () => {
                control.element.remove();
                if (!this.controlOptions.input.multiple) {
                    this.addControl();
                }
                control = null;
            });
            control.imageDiv.appendChild(cross);

            if (this.controlOptions.input.multiple) {
                this.addControl();
            }
        });
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
    }

    preview() {
        this.imageDiv = document.createElement('div');
        let url = URL.createObjectURL(this.input.files[0]);
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


/*
*
* <div class="input-picker d-flex flex-wrap">
                                    <div class="d-flex flex-wrap">
                                        <div style="background: url(http://shonirvor.local/storage/seed/user-photos/95.jpg)"
                                             class="picked-image rounded">
                                        </div>
                                        <label for="identities"
                                               class="input-image btn btn-light d-flex justify-content-center align-items-center">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                            <input id="identities" name="identities[]" type="file" accept="image/*"
                                                   class="d-none"
                                                   multiple>
                                        </label>
                                    </div>
*
* */
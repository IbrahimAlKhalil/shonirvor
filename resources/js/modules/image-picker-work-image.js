import '../../sass/frontend/components/_image-picker.scss';

export class ImagePicker {
    constructor(inputElements, callbacks = {}) {
        this.callbacks = callbacks;
        this.pickers = [];
        let inputs = [inputElements];
        let pickerClassList = ['image-picker'];
        let controlOptions = {
            classList: ['image-picker-control'],
            image: {
                classList: ['image-picker-image', 'shadow-sm', 'border']
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
                },
                callbacks: {
                    ...callbacks
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
        this.options = options;
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

            if (this.options.callbacks.hasOwnProperty('afterView')) {
                this.options.callbacks.afterView.call(this);
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
        let url = this.input.files[0] && this.input.files[0].type === 'application/pdf' ? 'data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjUxMnB4IiBoZWlnaHQ9IjUxMnB4IiB2aWV3Qm94PSIwIDAgNDU5IDQ1OSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNDU5IDQ1OTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8Zz4KCTxnIGlkPSJkcml2ZS1wZGYiPgoJCTxwYXRoIGQ9Ik0yMTEuNjUsMTQyLjhMMjExLjY1LDE0Mi44QzIxNC4yLDE0Mi44LDIxNC4yLDE0Mi44LDIxMS42NSwxNDIuOGMyLjU1LTEwLjIsNS4xLTE1LjMsNS4xLTIyLjk1di01LjEgICAgYzIuNTUtMTIuNzUsMi41NS0yMi45NSwwLTI1LjVjMCwwLDAsMCwwLTIuNTVsLTIuNTUtMi41NWwwLDBsMCwwYzAsMCwwLDIuNTUtMi41NSwyLjU1QzIwNi41NSwxMDIsMjA2LjU1LDExOS44NSwyMTEuNjUsMTQyLjggICAgTDIxMS42NSwxNDIuOHogTTEzNS4xNSwzMTguNzVjLTUuMSwyLjU1LTEwLjIsNS4xLTEyLjc1LDcuNjVjLTE3Ljg1LDE1LjMtMzAuNiwzMy4xNDktMzMuMTUsNDAuOGwwLDBsMCwwbDAsMCAgICBDMTA0LjU1LDM2NC42NSwxMTkuODUsMzQ5LjM1LDEzNS4xNSwzMTguNzVDMTM3LjcsMzE4Ljc1LDEzNy43LDMxOC43NSwxMzUuMTUsMzE4Ljc1QzEzNy43LDMxOC43NSwxMzUuMTUsMzE4Ljc1LDEzNS4xNSwzMTguNzV6ICAgICBNMzY5Ljc1LDI4MC41Yy0yLjU1LTIuNTUtMTIuNzUtMTAuMi00OC40NS0xMC4yYy0yLjU1LDAtMi41NSwwLTUuMSwwbDAsMGMwLDAsMCwwLDAsMi41NWMxNy44NSw3LjY1LDM1LjcsMTIuNzUsNDguNDUsMTIuNzUgICAgYzIuNTUsMCwyLjU1LDAsNS4xLDBsMCwwaDIuNTVjMCwwLDAsMCwwLTIuNTVsMCwwQzM3Mi4zLDI4My4wNSwzNjkuNzUsMjgzLjA1LDM2OS43NSwyODAuNXogTTQwOCwwSDUxQzIyLjk1LDAsMCwyMi45NSwwLDUxdjM1NyAgICBjMCwyOC4wNSwyMi45NSw1MSw1MSw1MWgzNTdjMjguMDUsMCw1MS0yMi45NSw1MS01MVY1MUM0NTksMjIuOTUsNDM2LjA1LDAsNDA4LDB6IE0zNzkuOTUsMzAwLjljLTUuMTAxLDIuNTUtMTIuNzUsNS4xLTIyLjk1LDUuMSAgICBjLTIwLjQsMC01MS01LjEtNzYuNS0xNy44NWMtNDMuMzUsNS4xLTc2LjUsMTAuMTk5LTEwMiwyMC4zOTljLTIuNTUsMC0yLjU1LDAtNS4xLDIuNTVjLTMwLjYsNTMuNTUxLTU2LjEsNzkuMDUxLTc2LjUsNzkuMDUxICAgIGMtNS4xLDAtNy42NSwwLTEwLjItMi41NTFsLTEyLjc1LTcuNjQ5di0yLjU1Yy0yLjU1LTUuMTAxLTIuNTUtNy42NS0yLjU1LTEyLjc1YzIuNTUtMTIuNzUsMTcuODUtMzUuNyw0OC40NS01My41NTEgICAgYzUuMS0yLjU1LDEyLjc1LTcuNjQ5LDIyLjk1LTEyLjc1YzcuNjUtMTIuNzUsMTUuMy0yOC4wNSwyNS41LTQ1Ljg5OWMxMi43NS0yNS41LDIwLjQtNTEsMjguMDUtNzMuOTVsMCwwICAgIGMtMTAuMi0zMC42LTE1LjMtNDguNDUtNS4xLTg0LjE1YzIuNTUtMTAuMiwxMC4yLTIwLjQsMjAuNC0yMC40aDUuMWM1LjEsMCwxMC4yLDIuNTUsMTUuMyw1LjFjMTcuODUxLDE3Ljg1LDEwLjIsNTguNjUsMCw5MS44ICAgIGMwLDIuNTUsMCwyLjU1LDAsMi41NWMxMC4yLDI4LjA1LDI1LjUsNTEsNDAuOCw2Ni4zYzcuNjUsNS4xLDEyLjc1LDEwLjIsMjIuOTUsMTUuM2MxMi43NSwwLDIyLjk1LTIuNTUsMzMuMTUtMi41NSAgICBjMzAuNiwwLDUxLDUuMSw1OC42NDksMTcuODVjMi41NTEsNS4xMDEsMi41NTEsMTAuMiwyLjU1MSwxNS4zQzM4Ny42LDI4OC4xNSwzODUuMDUsMjk1LjgsMzc5Ljk1LDMwMC45eiBNMjE0LjIsMjAxLjQ1ICAgIGMtNS4xLDE3Ljg1LTE1LjMsMzguMjUtMjUuNSw2MS4yYy01LjEsMTAuMTk5LTEwLjIsMTcuODUtMTUuMywyOC4wNWgyLjU1aDIuNTVsMCwwYzMzLjE1LTEyLjc1LDYzLjc1LTIwLjQsODQuMTUtMjIuOTUgICAgYy01LjEwMS0yLjU1LTcuNjUtNS4xLTEwLjItNy42NUMyMzkuNywyNDQuOCwyMjQuNCwyMjQuNCwyMTQuMiwyMDEuNDV6IiBmaWxsPSIjRDgwMDI3Ii8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==' : this.input.files[0] && /image\/.*/.test(this.input.files[0].type) ? URL.createObjectURL(this.input.files[0]) : this.input.getAttribute('data-image');
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
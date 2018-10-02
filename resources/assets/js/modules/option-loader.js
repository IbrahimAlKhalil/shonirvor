export class OptionLoader {
    constructor(select, options) {
        if (select instanceof Element) {
            select = [select];
        }
        this.options = {...options};

        select.forEach(select => {
            let target = document.querySelector(select.getAttribute('data-option-loader-target'));
            !select.hasAttribute('data-option-loader-nodisable') && this.disable(target);

            select.addEventListener('change', evt => {
                if (!select.value) {
                    evt.preventDefault();
                    evt.stopPropagation();
                    this.clean(target);
                    typeof this.options.emptyCallback === 'function' && this.options.emptyCallback.call(this, target, select);
                    return;
                }

                this.clean(target);
                this.loadOptions(`${select.getAttribute('data-option-loader-url')}?${select.getAttribute('data-option-loader-param')}=${select.value}`, target)
                    .then((data) => {
                        target.disabled = false;
                        typeof this.options.callback === 'function' && this.options.callback.call(this, target, data, select);
                    });
            });
        });
    }

    loadOptions(url, selectElement) {
        let arr = selectElement
            .getAttribute('data-option-loader-properties')
            .split(',');
        let properties = {};
        arr.forEach(item => {
            let keyVal = item.split('=');
            properties[keyVal[0]] = keyVal[1];
        });
        let previousCursor = getComputedStyle(document.body).cursor;
        document.body.style.cursor = 'wait';
        return new Promise(resolve => {
            fetch(url).then(response => {
                response.json().then(json => {
                    json.forEach(data => {
                        let option = document.createElement('option');
                        option.value = data[properties.value];
                        option.innerHTML = data[properties.text];
                        selectElement.appendChild(option);
                    });
                    document.body.style.cursor = previousCursor;
                    resolve(json);
                });
            });
        });
    }

    disable(select) {
        select.disabled = true;
    }

    clean(select) {
        select.innerHTML = '';
        this.disable(select);
        this.placehlder(select);

        if (select.hasAttribute('data-option-loader-target')) {
            let target = document.querySelector(select.getAttribute('data-option-loader-target'));
            this.clean(target);
        }
    }

    placehlder(select) {
        let placeholder = document.createElement('option');
        placeholder.selected = true;
        placeholder.value = '';
        placeholder.innerHTML = select.getAttribute('data-placeholder');
        select.insertBefore(placeholder, select.firstElementChild);
    }

}

document.addEventListener('DOMContentLoaded', () => {
    new OptionLoader(document.querySelectorAll('.option-loader'));
});
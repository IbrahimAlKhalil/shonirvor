export default function loadOptions(url, selectElement) {
    let arr = selectElement
        .getAttribute('data-option-loader-properties')
        .split(',');
    let properties = {};
    arr.forEach(item => {
        let keyVal = item.split('=');
        properties[keyVal[0]] = keyVal[1];
    });
    return new Promise(resolve => {
        fetch(url).then(response => {
            response.json().then(json => {
                json.forEach(data => {
                    let option = document.createElement('option');
                    option.value = data[properties.value];
                    option.innerHTML = data[properties.text];
                    selectElement.appendChild(option);
                });
                resolve(json);
            });
        });
    });
}

function cleanSelect(select) {
    select.innerHTML = '';
    if (select.hasAttribute('data-target-select')) {
        let target = document.querySelector(select.getAttribute('data-target-select'));
        target.innerHTML = '';
        target.disabled = true;
        if (target.hasAttribute('data-target-select')) {
            let targetTarget = document.querySelector(target.getAttribute('data-target-select'));
            cleanSelect(targetTarget);
            targetTarget.innerHTML = '';
            targetTarget.disabled = true;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-option-loader-url]').forEach(select => {
        let target = document.querySelector(select.getAttribute('data-target-select'));
        select.addEventListener('change', () => {
            cleanSelect(target);
            target.disabled = false;
            loadOptions(`${select.getAttribute('data-option-loader-url')}?${select.getAttribute('data-option-loader-param')}=${select.value}`, target)
                .then(() => {
                    let placeholder = document.createElement('option');
                    placeholder.selected=true;
                    placeholder.value="";
                    placeholder.innerHTML = target.getAttribute('data-placeholder');
                    target.insertBefore(placeholder, target.firstElementChild);
                });
        });
    });
});
function select(element, selector, all) {
    if (/(^\s*|,\s*)>/.test(selector)) {
        let removeId = false;
        if (!element.id) {
            element.id = 'ID_' + new Date().getTime();
            removeId = true;
        }
        selector = selector.replace(/(^\s*|,\s*)>/g, '$1#' + element.id + ' >');
        let result = all ? document.querySelectorAll(selector) : document.querySelector(selector);
        if (removeId) {
            element.id = null;
        }
        return result;
    } else {
        return all ? document.querySelectorAll(selector) : document.querySelector(selector);
    }
}

export function querySelector(element, selector) {
    return select(element, selector, false)
}

export function querySelectorAll(element, selector) {
    return select(element, selector, true)
}
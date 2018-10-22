export class UrlParser {
    constructor(urlString) {
        this.params = urlString.slice(1).split('&').map(param => {
            let obj = {};
            let keyVal = param.split('=');
            obj[keyVal[0]] = keyVal[1];
            return obj;
        }).reduce((prev, current) => {
            return {...prev, ...current};
        });
    }

    has(param) {
        return this.params.hasOwnProperty(param);
    }

    filled(param) {
        return !!this.params[param];
    }

    static parse(urlString) {
        return new UrlParser(urlString);
    }
}
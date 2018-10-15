export class UrlPerser {
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
}
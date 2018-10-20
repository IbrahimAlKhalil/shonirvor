export class UrlParser {
    constructor(urlString) {
        this.url = urlString;
        this.protocol = urlString.match(/^(http)s?:/)[0];
        this.params = urlString.slice(1).split('&').map(param => {
            let obj = {};
            let keyVal = param.split('=');
            obj[keyVal[0]] = keyVal[1];
            return obj;
        }).reduce((prev, current) => {
            return {...prev, ...current};
        });

        let fromProtocol = urlString.replace(/^(http)s?:\/\//, '');
        let fromPath = fromProtocol.slice(fromProtocol.indexOf('/') + 1, fromProtocol.length);
        let fromPathToParams = fromPath.slice(0, fromPath.indexOf('?'));
        this.pathName = fromPathToParams.slice(0, fromPathToParams.indexOf('#'));
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
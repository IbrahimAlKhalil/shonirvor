export default function (query) {
    const arr = query.slice(1)
        .split('&')
        .map(pair => pair.split('='))

    const obj = {}

    for (let elm of arr) {
        obj[elm[0]] = elm[1]
    }

    return obj
}

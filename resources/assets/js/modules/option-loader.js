function loadOptions(url, selectElement) {
    return new Promise(resolve => {
        fetch(url).then(response => {
            response.json().then(json => {
                json.forEach(data => {
                    let option = document.createElement('option');
                    option.value = data['id'];
                    option.innerHTML = data['bn_name'];
                    selectElement.appendChild(option);
                });
                resolve(json);
            });
        });
    });
}
function request(url, callback) {
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            callback(this.responseText);
            
        }
    }

    xhttp.open('post', url);
    xhttp.send();
}


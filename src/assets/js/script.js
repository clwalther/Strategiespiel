async function send(key, value) {
    for(let i = 0; i < key.length; i++) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", `/die-zauberer-schulen/scripts/__send__.php?Team=${window.location.search.split("=")[1]}`, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(encodeURIComponent(key[i]) +"=" + encodeURIComponent(value[i]));
    }

    xhr.onloadend = function() { location.reload(); }
}

document.onreadystatechange = function() {
if (document.readyState !== "complete") {
    document.getElementById("body").style.opacity = 0;
} else {
    document.getElementById("body").style.opacity = 1;
}
};

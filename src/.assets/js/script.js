(() => {
    let body;
    let dialog;

    // === EVENTS ===
    document.addEventListener("readystatechange", (event) => {
        body = document.getElementById("body");
        dialog = document.getElementById("dialog");
    });
    document.addEventListener("click", (event) => {
        if(event.explicitOriginalTarget == dialog) {
            dialog.open && !clicked_element(dialog.children[0], event.target) ? close_dialog() : false;
        }
    });

    window.addEventListener("load", (event) => {
        body.style.opacity = 1;
    });
    window.addEventListener("beforeunload", (event) => {
        body.style.opacity = 0;
    });
    window.addEventListener("keydown", (event) => {
        event.keyCode == 27 ? close_dialog() : false; // key code 27 = ESCAPE
    });


    // === FUNCTIONS ===
    function clicked_element(element, target) {
        if(target != null) {
            if(target == element) {
                return true;
            } else {
                return clicked_element(element, target.parentElement);
            }
        }

        return false;
    }
})();

// === DIALOG ===
function open_dialog(name, id) {
    // aquries the element "dialog"
    let dialog = document.getElementById("dialog");

    // removes the enabled class from each element
    for(var i = 0; i < dialog.childElementCount; i++) {
        dialog.children[i].classList.remove("enabled-dialog");
    }
    // adds the enabled calss to the passed element and opens the dialog
    get_dialog(name, id).classList.add("enabled-dialog");
    dialog.show();
}

function close_dialog() {
    // aquries the element "dialog"
    let dialog = document.getElementById("dialog");

    // closes the dialog
    dialog.close();
}

function get_dialog(name, id) {
    return document.getElementById(`dialog-${name}-${id}`);
}

function get_dialog_action_button(name, id) {
    return get_dialog(name, id).lastChild.lastChild;
}

// === BACKEND COMS ===
function send(command) {
    const xhr = new XMLHttpRequest();

    xhr.open("POST", `/${window.location.pathname.split("/")[1]}/*command/`);
    xhr.setRequestHeader("Content-Type", "multipart/form-data; charset=UTF-8");
    xhr.onload = () => {
        console.log(xhr.responseText);

        if (xhr.status != 200) {
            alert(`ERROR: ${xhr.status}.<br>This error occured when trying to access to backend.`);
        }
    };
    xhr.send(command);
}

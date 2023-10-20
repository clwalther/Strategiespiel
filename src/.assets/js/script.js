// === GLOBAL VARIABLES ===
let body;
let dialog;

// === EVENTS ===
// onload event
document.addEventListener("readystatechange", (event) => {
    // global
    body = document.getElementById("body");
    dialog = document.getElementById("dialog");
});
// load event as soon as all content is fully loaded
window.addEventListener("load", (event) => {
    body.style.opacity = 1;
});
window.addEventListener("beforeunload", (event) => {
    body.style.opacity = 0;
});
// keystroke detection TODO (doesn't work)
window.addEventListener("keydown", (event) => {
    event.key == 27 ? close_dialog() : false; // key code 27 = ESCAPE
});
// mouse clicks detection
document.addEventListener("click", (event) => {
    if(event.explicitOriginalTarget == dialog) {
        dialog.open && !clicked_element(dialog.children[0], event.target) ? close_dialog() : false;
    }
});

// === FUNCTIONS ===
// util to find the generally clicked element
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

// === DIALOG ===
function open_dialog(name, id) {
    // removes the enabled class from each element
    for(var i = 0; i < dialog.childElementCount; i++) {
        dialog.children[i].classList.remove("enabled-dialog");
    }
    // adds the enabled calss to the passed element and opens the dialog
    document.getElementById(`dialog-${name}-${id}`).classList.add("enabled-dialog");
    dialog.show();
}

function close_dialog() {
    // closes the dialog
    dialog.close();
}

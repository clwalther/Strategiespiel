/* This is the javascript for the general interfaces needed to support the
users interface. Main design requirment is again stackabilitly and writing
as generically as possible  */

// === VARIABLES ====
const dialog = document.getElementById("dialog");

// === EVENTS ===
// keystroke detection
addEventListener("keydown", (event) => {
    event.keyCode == 27 ? close_dialog() : false; // key code 27 = ESCAPE
});
// mose clicks detection
addEventListener("click", (event) => {
    if(event.explicitOriginalTarget == dialog) {
        dialog.open && !clicked_element(dialog.children[0], event.target) ? close_dialog() : false;
    }
});

// === FUNCTIONS ===
function highligth_team() {
    if(window.location.search.split("=")[0] == "?Team") {
        let team = window.location.search.split("=")[1];
        let team_button = document.getElementById(team);

        team_button.classList.add("active_button");
    } else {
        window.open('./index.php?Team=1', '_self');
    }
}

function clicked_element(element, target) {
    if(target == element) {
        return true;
    } else if(target != null) {
        return clicked_element(element, target.parentElement);
    } else {
        return false;
    }
}

// methods around the dialog filed
function open_dialog(/* args */) {
    // TODO: loading the content for the dialog
    dialog.show();
}

function close_dialog() {
    dialog.close();
}


// === END ===
// on window load ...
highligth_team();

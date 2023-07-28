// === GLOBAL VARIABLES ===
let team_id;

let body;
let dialog;

// === EVENTS ===
// onload event
document.addEventListener("readystatechange", (event) => {
    get_team();
    route_to_team();

    // global
    body = document.getElementById("body");
    dialog = document.getElementById("dialog");

    __get();
});
// load event as soon as all content is fully loaded
window.addEventListener("load", (event) => {
    body.style.opacity = 1;
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
// aquires the correct team identifier
function get_team() {
    // looping through all passed queries
    window.location.search.split("?").forEach(query => {
        // checks for query key: "Team"
        if(query.split("=")[0] === "Team") {
            // sets the int team identifer
            team_id = parseInt(query.split("=")[1]);
        }
    });
}

// generates the query string for sending data to the backend
function get_message_string(key, value) {
    let queries = [];

    // pushes all wanted queries into array
    for(var i = 0; i < Math.min(key.length, value.length); i++) {
        queries.push(encodeURI(key[i]) + "=" + encodeURI(value[i]));
    }
    // joins all entries with query seperators: "?"
    return queries.join("?");
}

// routing to the correct Team location
function route_to_team() {
    // checks whether the team is defined
    if(team_id === undefined) {
        // reroutes to know location
        window.open('./index.php?Team=1', '_self');
    }
}

// util to find the generally clicked element
function clicked_element(element, target) {
    if(target != null) {
        return clicked_element(element, target.parentElement);
    } else {
        return target == element;
    }
}

// === DIALOG ===
function open_dialog(id) {
    // removes the enabled class from each element
    for(var i = 0; i < dialog.childElementCount; i++) {
        dialog.children[i].classList.remove("enabled-dialog");
    }
    // adds the enabled calss to the passed element and opens the dialog
    document.getElementById(id).classList.add("enabled-dialog");
    dialog.show();
}

function close_dialog() {
    // closes the dialog
    dialog.close();
}

// === BACKEND COMMUNICATION ===
async function __send(key, value) {
    // create the XMLHttpRequest (post)
    let xhr = new XMLHttpRequest();
    xhr.open("POST", `/die-zauberer-schulen/scripts/__send__.php?Team=${team_id}`, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    console.log(get_message_string(key, value));
    xhr.send(get_message_string(key, value));
    // reload the current location
    xhr.onloadend = function (event) { location.reload(); }
}

async function __get() {
    let response = await fetch(`/die-zauberer-schulen/scripts/__get__.php?Team=${team_id}`);
    let data = await response.json();
    initialize(data, team_id);
}

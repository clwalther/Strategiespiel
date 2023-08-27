// === GLOBAL VARIABLES ===
let team_id;

let body;
let dialog;

// === EVENTS ===
// onload event
document.addEventListener("readystatechange", (event) => {
    get_team();

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

function get_message_string(keys, values) {
    parameters = [];
    value_list = [];
    queries = [];

    for(let i = 0; i < Math.min(keys.length, values.length); i++) {
        keys[i] = encodeURI(keys[i]);
        values[i] = encodeURI(values[i]);

        if(parameters.includes(keys[i])) {
            query_index = parameters.indexOf(keys[i]);
            value_list[query_index].push(values[i]);
        } else {
            parameters.push(keys[i]);
            value_list.push([values[i]]);
        }
    }

    for(let j = 0; j < parameters.length; j++) {
        queries.push(`${parameters[j]}=${value_list[j].join(",")}`)
    }

    return queries.join("&");
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
async function __send(keys, values) {
    // create the XMLHttpRequest (post)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", `/die-zauber-schulen/scripts/__send__.php?Team=${team_id}`, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(get_message_string(keys, values));

    xhr.onloadend = function(event) { location.reload(); }
}

async function __get() {
    let response = await fetch(`/die-zauber-schulen/scripts/__get__.php?Team=${team_id}`);
    let data = await response.json();
    initialize(data, team_id);
}

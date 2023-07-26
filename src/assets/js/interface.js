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
// team and loading and stuff
function solve_check_team() {
    if(window.location.search.split("=")[0] != "?Team") {
        window.open('./index.php?Team=1', '_self');
    }
}

// methods around the dialog filed
function open_dialog(id) {
    for(var i = 0; i < dialog.childElementCount; i++) {
        dialog.children[i].classList.remove("enabled-dialog");
    }
    document.getElementById(id).classList.add("enabled-dialog");
    dialog.show();
}

function close_dialog() {
    dialog.close();
}

// utils
function clicked_element(element, target) {
    if(target != null) {
        return clicked_element(element, target.parentElement);;
    } else if(target == element) {
        return true;
    } else {
        return false;
    }
}

// === CLAESS === (i know its not beautiful - ok)
class General
{
    constructor() {
        this.team_drawer = document.getElementById("team-drawer");
        this.team_name = document.getElementById("team-name");

        this.fetch_data_and_go();
    }

    async fetch_data_and_go() {
        const response = await fetch(`/die-zauberer-schulen/scripts/__get__.php?Team=${window.location.search.split("=")[1]}`);
        const full_data = await response.json();

        this.general_data = full_data.general;

        this.generate_team_drawer_entries();
        this.generate_team_name();
    }

    generate_team_drawer_entries() {
        this.general_data.teams.forEach(team => {
            let button_struct = `
            <button
                class="${this.get_team_drawer_button_status(team)}"
                id="team-button-${team.group_id}"
                onclick="window.open('./index.php?Team=${team.group_id}', '_self');">

                <img src="../../../../assets/imgs/group.svg">

                &nbsp;&nbsp;

                <span>Team ${team.group_id}  -  ${team.teamname}</span>
            </button>`;

            this.team_drawer.innerHTML += button_struct;
        });

    }

    generate_team_name() {
        var team_index = parseInt(window.location.search.split("=")[1]) - 1;
        var team_id = this.general_data.teams[team_index].group_id;
        var team_name = this.general_data.teams[team_index].teamname;
        this.team_name.innerHTML = `Team ${team_id} - ${team_name}`;
    }

    get_team_drawer_button_status(team) {
        if(team.group_id ==  window.location.search.split("=")[1]) {
            return "active_button";
        }
        return "";
    }
}

class Buildings
{
    constructor() {
        this.tree = document.getElementById("tree");
        this.dialog = document.getElementById("dialog");
        this.connections = document.getElementById("tree-connection");

        this.building_data;

        this.fetch_data_and_go();
    }

    async fetch_data_and_go() {
        const response = await fetch(`/die-zauberer-schulen/scripts/__get__.php?Team=${window.location.search.split("=")[1]}`);
        const full_data = await response.json();

        this.building_data = full_data.school_admin.buildings;

        this.generate_tree();
        this.generate_dialog_cards();
    }

    generate_tree() {
        let tree = "";

        this.building_data.forEach(main_branch => {
            let main_branch_html = "";

            main_branch.forEach(level => {
                let level_html = "";

                level.forEach(building => {
                    let status = this.get_tree_button_status(building);

                    let button_struct = `<button ${status} onclick='open_dialog("dialog-buildings-${building.id}");'>${building.name}</button>`;
                    level_html += button_struct;
                });

                main_branch_html += `<div>${level_html}</div>`;
            });

            tree += `<div>${main_branch_html}</div>`;
        });

        this.tree.innerHTML = tree;
    }

    get_tree_button_status(building) {
        if(building.active) { var status = `class="enabled"`; }
        else if (building.parent_active) { var status = ``; }
        else { var status = `disabled`; }

        return status;
    }

    generate_tree_connections() {
        // TODO
    }

    generate_dialog_cards() {
        this.building_data.forEach(main_branch => {
            main_branch.forEach(level => {
                level.forEach(building => {
                    this.dialog.innerHTML += `
                    <div id="dialog-buildings-${building.id}">
                        <h1>${building.name.replace("-", "")}</h1>
                        <h4>Benötigt:</h4>
                        <ul>
                            ${this.generate_html_list(building.requriments)}
                        </ul>
                        <h4>Erträge:</h4>
                        <ul>
                            ${this.generate_html_list(building.yields)}
                        </ul>
                        <section>
                            <button onclick="close_dialog();">Schließen</button>
                            ${this.get_dialog_button_status(building)}
                        </section>
                    </div>`;
                });
            });
        });
    }

    generate_html_list(array) {
        let html = "";

        array.forEach(element => {
            html += `<li>${element}</li>`;
        });

        return html;
    }

    get_dialog_button_status(building) {
        if(building.active) {
            var colour = "red";
            var name = "Deaktivieren";
            var click  = `send(['deacitvate_building_id'], [${building.id}])`;
        } else {
            var colour = "green";
            var name = "Aktivieren";
            var click  = `send(['acitvate_building_id'], [${building.id}])`;
        }
        return `<button onclick="${click}" style="background-color: var(--colour-${colour})">${name}</button>`
    }
}

class Students
{
    constructor() {
        this.dialog = document.getElementById("dialog");
        this.n_not_fetched = document.getElementById("students-n-not-fetched");

        this.student_data;

        this.fetch_data_and_go();
    }

    async fetch_data_and_go() {
        const response = await fetch(`/die-zauberer-schulen/scripts/__get__.php?Team=${window.location.search.split("=")[1]}`);
        const full_data = await response.json();

        this.student_data = full_data.school_admin.students;

        this.generate_n_not_fetched();
        this.generate_dialog_cards();
    }

    generate_n_not_fetched() {
        this.n_not_fetched.innerHTML = this.student_data.length;
    }

    generate_dialog_cards() {
        this.dialog.innerHTML += `
            <div id="dialog-student">
                <h1>Schüler auschecken</h1>

                ${this.get_student_cards()}
                <section>
                    <button onclick="close_dialog();">Schließen</button>
                    <button onclick="students.send();" style="background-color: var(--colour-green)">Ausschecken</button>
                </section>
            </div>`;
    }

    get_student_cards() {
        let student_cards = "";
        this.student_data.forEach(student => {
            let skill_bar = "";
            student.skills.forEach(skill => {
                skill_bar += this.get_skill_bar(skill);
            });
            student_cards += `<ul>${skill_bar}</ul>`;
        });
        return student_cards;
    }

    get_skill_bar(skill) {
        let skill_eval = "";

        for(let i = 0; i < 5; i++) {
            if(i == skill.value) {
                skill_eval += `<input disabled type="checkbox" checked>`;
            } else {
                skill_eval += `<input disabled type="checkbox">`;
            }
        }
        return `
        <li>
            <span>${skill.name}</span>
            <div>
                ${skill_eval}
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input disabled type="checkbox">
                <input disabled type="checkbox">
            </div>
        </li>`;
    }
}

// === END ===
// on window load ...
solve_check_team();
var general = new General;
var buildings = new Buildings;
var students = new Students;


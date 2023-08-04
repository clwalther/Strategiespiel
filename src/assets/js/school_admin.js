// === GLOBAL VARIABLES ===
let team_drawer;
let teamname;
let building_tree;
let buildings_connections;
let not_fetched;
let teacher_slot;

let students;

// === CLAESS ===
class Students
{
    constructor(data, team_id) {
        this.data = data;

        this.loaded = 0;
        this.cached = [];

        this.generate_not_fetched();
        this.generate_dialog_card();
    }

    generate_not_fetched() {
        not_fetched.innerText = this.data.school_admin.students.length;
    }

    generate_dialog_card() {
        let dialog_card = new DialogCard("students", 0);
        let right = document.createElement("button");
        let left = document.createElement("button");
        let right_icon = document.createElement("img");
        let left_icon = document.createElement("img");
        let student_container = document.createElement("div");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(left);
        dialog_card.container.appendChild(right);
        dialog_card.container.appendChild(student_container);
        right.appendChild(right_icon);
        left.appendChild(left_icon);

        dialog_card.header.innerText = `Schüler auschecken 0/0`;

        right.classList.add("student-navigation");
        left.classList.add("student-navigation");
        right_icon.src = "../../../../assets/imgs/right.svg";
        left_icon.src = "../../../../assets/imgs/left.svg";

        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Ausschecken";

        for(let student_index = 0; student_index < this.data.school_admin.students.length; student_index++) {
            let student = this.data.school_admin.students[student_index];

            let skill_card = new SkillCard(student.skills);
            let skill_card_html = skill_card.generate(true, true);

            student_container.appendChild(skill_card_html);

            skill_card_html.classList.add("noselect");

            if(student_index == 0) {
                skill_card_html.classList.add("enabled-student");
                dialog_card.header.innerText = `Schüler auschecken ${this.loaded + 1}/${this.data.school_admin.students.length}`;
            }

            skill_card_html.addEventListener("click", (event) => {
                if(this.cached.includes(student.id)) {
                    skill_card_html.classList.remove("active-student");
                    this.cached.splice(this.cached.indexOf(student.id), 1);
                } else {
                    skill_card_html.classList.add("active-student");
                    this.cached.push(student.id);
                }
            });
        }

        right.addEventListener("click", (event) => {
            if(student_container.childElementCount) {
                // collapses skill of old student
                student_container.children[this.loaded].classList.remove("enabled-student");
                // changes the index of student that should be used (adding)
                this.loaded >= student_container.childElementCount - 1 ? this.loaded = 0: this.loaded++;
                // makes new student visible
                student_container.children[this.loaded].classList.add("enabled-student");
                // updates the header
                dialog_card.header.innerText = `Schüler auschecken ${this.loaded + 1}/${this.data.school_admin.students.length}`;
            }
        });
        left.addEventListener("click", (event) => {
            if(student_container.childElementCount) {
                // collapses skill of old student
                student_container.children[this.loaded].classList.remove("enabled-student");
                // changes the index of student that should be used (subtracting)
                this.loaded <= 0 ? this.loaded = student_container.childElementCount - 1 : this.loaded--;
                // makes new student visible
                student_container.children[this.loaded].classList.add("enabled-student");
                // updates the header
                dialog_card.header.innerText = `Schüler auschecken ${this.loaded + 1}/${this.data.school_admin.students.length}`;
            }
        });

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
        });
        dialog_card.submit.addEventListener("click", (event) => {
            if(this.cached.length > 0) {
                let keys = Array(this.cached.length).fill("students_check_out");
                __send(keys, this.cached);
            }
        });
    }
}

class Teachers
{
    constructor(data, team_id) {
        this.data = data;

        this.cached_changes_base = [];
        this.cached_changes_advanced = [];

        this.generate_panels();
        this.generate_dialog_cards();
    }

    generate_panels() {
        this.data.school_admin.teachers.forEach(teacher => {
            let teacher_card = new PanelCard(teacher.name);

            teacher.skills.forEach(skill => {
                if(skill.name === teacher.name) {
                    teacher_slot.appendChild(teacher_card.generate());
                    teacher_card.add_display(skill.advanced + 1, "../../../../assets/imgs/education.svg");
                    teacher_card.add_display(skill.base + 1, "../../../../assets/imgs/star.svg");

                    teacher_card.action_button.addEventListener("click", (event) => {
                        open_dialog(`dialog-teacher-${teacher.name}`);
                    });
                }
            });
        });
    }

    generate_dialog_cards() {
        this.data.school_admin.teachers.forEach(teacher => {
            let dialog_card = new DialogCard("teacher", teacher.name);
            let teacher_card = new SkillCard(teacher.skills);

            dialog.appendChild(dialog_card.generate());
            dialog_card.container.appendChild(teacher_card.generate(false, false));

            dialog_card.header.innerText = `${teacher.name} Lehrer`;
            dialog_card.cancel.innerText = "Schließen";
            dialog_card.submit.innerText = "Aktualisieren";

            dialog_card.cancel.addEventListener("click", (event) => {
                // dialog
                close_dialog();
                // resets
                teacher_card.reset();
            });
            dialog_card.submit.addEventListener("click", (event) => {
                let keys = Array();
                let values = Array();

                teacher_card.base_cached_changes.forEach(struct => {
                    keys.push("teachers_set_base");
                    values.push(`${teacher.name};${struct}`);
                });

                teacher_card.advanced_cached_changes.forEach(struct => {
                    keys.push("teachers_set_advanced");
                    values.push(`${teacher.name};${struct}`);
                });

                __send(keys, values);
            });
        });
    }
}

class Buildings
{
    constructor(data, team_id) {
        this.data = data;

        this.generate_building_tree();
        this.generate_connections();
        this.generate_dialog_cards(this.data.school_admin.buildings);
    }

    generate_building_tree() {
        Object.keys(this.data.school_admin.buildings).forEach(building_name => {
            let building = this.data.school_admin.buildings[building_name];
            let branch = this.generate_building_branch(building_name, building, 0, true);

            building_tree.appendChild(branch);
        });
    }

    generate_building_branch(building_name, building, prev_level, prev_acitve) {
        const MARGIN_TOP = 86; // constant: 86px (derived from the css rules of #buildings)

        let branch = document.createElement("div");
        let panel = document.createElement("button");
        let knot = document.createElement("div");

        branch.appendChild(panel);
        branch.appendChild(knot);

        branch.style.marginTop = `${MARGIN_TOP * (building.level - prev_level - 1)}px`;
        branch.classList.add("building-tree-branch");
        knot.classList.add("building-tree-knot");

        building.active ? panel.classList.add("active-building") : false;

        panel.innerText = building.trivialname;
        panel.disabled = !prev_acitve;

        if(building.children != "none") {
            Object.keys(building.children).forEach(child_name => {
                let child = building.children[child_name];
                let branch = this.generate_building_branch(child_name, child, building.level, building.active);

                knot.appendChild(branch);
            });
        }

        panel.addEventListener("click", (event) => {
            open_dialog(`dialog-buildings-${building_name}`);
        })

        return branch;
    }

    generate_connections() {
        let branches = document.getElementsByClassName("building-tree-branch");

        for(let branch_index = 0; branch_index < branches.length; branch_index++) {
            let org_button = branches[branch_index].children[0].getBoundingClientRect();

            for(let connection_index = 0; connection_index < branches[branch_index].children[1].children.length; connection_index++) {
                let end_button = branches[branch_index].children[1].children[connection_index].children[0].getBoundingClientRect();
                let connection = document.createElementNS("http://www.w3.org/2000/svg", "path");

                let x_org = org_button.left + org_button.width / 2 - building_tree.getBoundingClientRect().left;
                let y_org = org_button.top  + org_button.height    - building_tree.getBoundingClientRect().top;

                let x_end = end_button.left + end_button.width / 2 - building_tree.getBoundingClientRect().left;
                let y_end = end_button.top  - building_tree.getBoundingClientRect().top;

                let x_rel = (x_end - x_org) / 2;
                let y_rel = (y_end - y_org) / 2;

                let path = `M ${x_org} ${y_org}
                            q${0} ${y_rel} ${x_rel} ${y_rel}
                            q${x_rel} ${0} ${x_rel} ${y_rel}`;

                connection.setAttributeNS(null, "d", path);

                buildings_connections.appendChild(connection);
            }
        }
    }

    generate_dialog_cards(element) {
        Object.keys(element).forEach(building_name => {
            let building = element[building_name];

            let dialog_card = new DialogCard("buildings", building_name);
            let requirements_header = document.createElement("h4");
            let requirements = document.createElement("ul");
            let yields_header = document.createElement("h4");
            let yields = document.createElement("ul");

            dialog.appendChild(dialog_card.generate());
            dialog_card.container.appendChild(requirements_header);
            dialog_card.container.appendChild(requirements);
            dialog_card.container.appendChild(yields_header);
            dialog_card.container.appendChild(yields);

            dialog_card.header.innerText = `${building.trivialname.replace("-", "")}`;
            requirements_header.innerText = "Benötigt:";
            yields_header.innerText = "Erträge:";
            dialog_card.cancel.innerText = "Schließen";
            dialog_card.submit.innerText = building.active ? "Deaktivieren" : "Aktivieren";
            dialog_card.submit.style.backgroundColor = building.active ? "var(--colour-red)" : "var(--colour-green)";

            building.requirements.forEach(requirement_value => {
                let list_element = document.createElement("li");
                requirements.appendChild(list_element);
                list_element.innerText = requirement_value;
            });

            building.yields.forEach(yield_value => {
                let list_element = document.createElement("li");
                yields.appendChild(list_element);
                list_element.innerText = yield_value;
            });

            dialog_card.cancel.addEventListener("click", (event) => {
                close_dialog();
            });
            dialog_card.submit.addEventListener("click", (event) => {
                let callback = building.active ? "building_deactivate" : "building_activate"
                __send([callback], [building_name]);
            });

            if(building.children != "none") {
                this.generate_dialog_cards(building.children);
            }
        });
    }
}

function initialize(data, team_id) {
    // team
    team_drawer = document.getElementById("team-drawer");
    teamname = document.getElementById("teamname");
    // building
    building_tree = document.getElementById("building-tree");
    buildings_connections = document.getElementById("buildings-connections");
    // students
    not_fetched = document.getElementById("students-not-fetched");
    // teachers
    teacher_slot = document.getElementById("teacher-slot");

    new General(data, team_id);
    students = new Students(data, team_id);
    new Teachers(data, team_id);
    new Buildings(data, team_id);
}

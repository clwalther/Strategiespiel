// === GLOBAL VARIABLES ===
let team_drawer;
let teamname;
let building_tree;
let buildings_connections;
let not_fetched;
let teacher_container;

let students;

// routing to the correct Team location
function route_team() {
    let team_id;
    // looping through all passed queries
    window.location.search.split("?").forEach(query => {
        // checks for query key: "Team"
        if(query.split("=")[0] === "Team") {
            // sets the int team identifer
            team_id = parseInt(query.split("=")[1]);
        }
    });
    // checks whether the team is defined
    if(team_id === undefined) {
        // reroutes to know location
        window.open('./index.php?Team=1', '_self');
    }
};

route_team();

// === CLAESS ===
class General
{
    constructor(data, team_id) {
        this.data = data;
        this.team_id = team_id;
        this.teamname = this.data.general.teams[this.team_id - 1].teamname;

        this.generate_team_drawer();
        this.generate_teamname();
        this.generate_dialog_card();
        this.generate_time_interval();
    }

    generate_team_drawer() {
        // go through every team
        this.data.general.teams.forEach(team => {
            // generate button element
            let button = this.generate_button_struct(team);
            // add element to team_drawer
            team_drawer.appendChild(button);
        });
    }

    generate_button_struct(team) {
        let status = this.get_button_status(team);

        let button = document.createElement("button");
        let image = document.createElement("img");
        let span = document.createElement("span");

        button.appendChild(image);
        button.appendChild(span);
        button.classList.add(status);
        button.addEventListener("click", (event) => {
            window.open(`./index.php?Team=${team.group_id}`, '_self');
        })

        image.src = "../../../../assets/imgs/group.svg";

        span.innerText = `Team ${team.group_id}  -  ${team.teamname}`;

        return button;
    }

    get_button_status(team) {
        return team.group_id == this.team_id ? "active_button" : "inactive_button";
    }

    generate_teamname() {
        teamname.innerText = `Team ${this.team_id} - ${this.teamname}`;
    }

    generate_dialog_card() {
        let dialog_card = document.createElement("div");
        let header = document.createElement("h1");
        let paragraph = document.createElement("p");
        let text_inp = document.createElement("input");
        let button_container = document.createElement("div");
        let cancle = document.createElement("button");
        let submit = document.createElement("button");

        dialog.appendChild(dialog_card);

        dialog_card.appendChild(header);
        dialog_card.appendChild(paragraph);
        dialog_card.appendChild(text_inp);
        dialog_card.appendChild(button_container);

        button_container.appendChild(cancle);
        button_container.appendChild(submit);

        dialog_card.id = "dialog-general-name";

        header.innerText = "Team Name ändern";
        paragraph.innerText = `Ändere den Team Namen hier und auf der vorliegenden Team Zettel.
                                Teams sollten nicht ihrer Zeit verschwenden ihren Namen die ganze Zeit zu ändern.`;
        button_container.classList.add("button-container");


        text_inp.type = "text";
        text_inp.placeholder = "Team Name";
        text_inp.value = this.teamname;

        cancle.innerText = "Schließen";
        submit.innerText = "Ändern";
        submit.style.background = "var(--colour-green)";

        cancle.addEventListener("click", (event) => {
            close_dialog();
            text_inp.value = this.teamname;
        })
        submit.addEventListener("click", (event) => {
            // aquire the new teamname and remove blank spaces
            let new_teamname = text_inp.value.trim();
            // check if teamname is valid
            if(new_teamname != "") {
                // send data (<team_id>;<teamname>) to backend
                __send(["general_change_name"], [`${team_id};${new_teamname}`]);
            }
        })
    }

    generate_time_interval() {
        setInterval(function () {
            let elements = document.getElementsByTagName("time");

            for(let index = 0; index < elements.length; index++) {
                elements[index].innerText = "TODO: time";
            }
        }, 999);
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

    // BUILDING TREE
    generate_building_tree() {
        Object.keys(this.data.school_admin.buildings).forEach(building_name => {
            let building = this.data.school_admin.buildings[building_name];
            this.generate_building_branch(building_name, building, building_tree, building.level);
        });
    }

    generate_building_branch(building_name, building, parent_branch, delta) {
        let branch = document.createElement("div");
        let label = document.createElement("button");
        let knot = document.createElement("div");

        branch.classList.add(`building-tree-level-${building.level}`);
        branch.classList.add("building-tree-branch");
        knot.classList.add("building-tree-knot");

        label.innerText = building.trivialname;

        this.create_margin(delta, branch);
        this.create_label_status(building.active, label);
        this.is_label_disabled(building.parent_active, label)

        label.addEventListener("click", (event) => {
            open_dialog(`dialog-buildings-${building_name}`);
        });

        parent_branch.appendChild(branch);
        branch.appendChild(label);
        branch.appendChild(knot);

        if(building.children !== "none") {
            Object.keys(building.children).forEach(child_name => {
                let child = building.children[child_name];
                this.generate_building_branch(child_name, child, knot, child.level - building.level);
            });
        }
    }

    create_label_status(active, label) {
        active ? label.classList.add("active-building") : false;
    }

    is_label_disabled(parent_active, label) {
        label.disabled = !parent_active;
    }

    create_margin(delta, branch) {
        const MARGIN = 86;
        branch.style["margin-top"] = `${MARGIN * (delta - 1)}px`;
    }

    // BUILDING TREE CONNECTIONS
    generate_connections() {
        let branches = document.getElementsByClassName("building-tree-branch");

        for(let branch_index = 0; branch_index < branches.length; branch_index++) {
            let org_button = branches[branch_index].children[0].getBoundingClientRect();

            for(let connection_index = 0; connection_index < branches[branch_index].children[1].children.length; connection_index++) {
                let end_button = branches[branch_index].children[1].children[connection_index].children[0].getBoundingClientRect();
                let connection = document.createElementNS("http://www.w3.org/2000/svg", "path");

                let x_org = org_button.left + org_button.width / 2 - building_tree.getBoundingClientRect().left;
                let y_org = org_button.top  + org_button.height    - building_tree.getBoundingClientRect().top;

                let x_end = end_button.left + end_button.width / 2    - building_tree.getBoundingClientRect().left;
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

    // DIALOG CARDS
    generate_dialog_cards(element) {
        Object.keys(element).forEach(building_name => {
            let building = element[building_name];

            let dialog_card = document.createElement("div");
            let header = document.createElement("h1");
            let requriments_header = document.createElement("h4");
            let requriments = document.createElement("ul");
            let yields_header = document.createElement("h4");
            let yields = document.createElement("ul");
            let button_container = document.createElement("div");
            let cancle = document.createElement("button");
            let submit = document.createElement("button");

            dialog.appendChild(dialog_card);

            dialog_card.appendChild(header);
            dialog_card.appendChild(requriments_header);
            dialog_card.appendChild(requriments);
            dialog_card.appendChild(yields_header);
            dialog_card.appendChild(yields);
            dialog_card.appendChild(button_container);

            this.generate_dialog_card_list(requriments, building.requirements);
            this.generate_dialog_card_list(yields, building.yields);

            button_container.appendChild(cancle);
            button_container.appendChild(submit);

            dialog_card.id = `dialog-buildings-${building_name}`;
            header.innerText = `${building.trivialname.replace("-", "")}`;
            requriments_header.innerText = "Benötigt:";
            yields_header.innerText = "Erträge:";
            cancle.innerText = "Schließen";

            button_container.classList.add("button-container");


            this.get_dialog_card_submit_name(building, submit);
            this.get_dialog_card_submit_colour(building, submit);

            cancle.addEventListener("click", (event) => {
                close_dialog();
            })
            submit.addEventListener("click", (event) => {
                // aquire callback
                let callback = this.get_dialog_card_callback(building);
                // send data (acitvate_building_id/deacitvate_building_id -> buildings.id) to backend
                __send([callback], [building_name]);
            });

            if(building.children != "none") {
                this.generate_dialog_cards(building.children);
            }
        });
    }

    generate_dialog_card_list(html_element, array) {
        array.forEach(elements => {
            let element = document.createElement("li");
            element.innerText = elements;
            html_element.appendChild(element);
        });
    }

    get_dialog_card_submit_name(building, element) {
        element.innerText = building.active ? "Deaktivieren" : "Aktivieren";
    }

    get_dialog_card_submit_colour(building, element) {
        element.style.background =  `var(--colour-${building.active ? "red" : "green"})`;
    }

    get_dialog_card_callback(building) {
        return building.active ? "deacitvate_building" : "acitvate_building";
    }
}

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
        let dialog_card = document.createElement("div");
        let header = document.createElement("h1");
        let main_container = document.createElement("div");
        let right = document.createElement("button");
        let left = document.createElement("button");
        let right_icon = document.createElement("img");
        let left_icon = document.createElement("img");
        let student_container = document.createElement("div");
        let button_container = document.createElement("div");
        let cancle = document.createElement("button");
        let submit = document.createElement("button");

        dialog.appendChild(dialog_card);

        dialog_card.appendChild(header);
        dialog_card.appendChild(main_container);
        dialog_card.appendChild(button_container);
        main_container.appendChild(left);
        main_container.appendChild(student_container);
        main_container.appendChild(right);
        right.appendChild(right_icon);
        left.appendChild(left_icon);

        this.generate_dialog_card_student_cards(student_container);

        button_container.appendChild(cancle);
        button_container.appendChild(submit);

        dialog_card.id = "dialog-student";
        header.id = "dialog-student-header";
        student_container.id = "dialog-student-student";
        button_container.classList.add("button-container");
        right.classList.add("student-navigation");
        left.classList.add("student-navigation");
        right_icon.src = "../../../../assets/imgs/right.svg";
        left_icon.src = "../../../../assets/imgs/left.svg";

        cancle.innerText = "Schließen";
        submit.innerText = "Ausschecken";
        submit.style.background = "var(--colour-green)";

        right.addEventListener("click", (event) => {
            this.loaded++;
            if(this.loaded >= student_container.childElementCount) {
                this.loaded = 0;
            }
            this.update_dialog_cards_student_cards();

        });
        left.addEventListener("click", (event) => {
            if(this.loaded <= 0) {
                this.loaded = student_container.childElementCount;
            }
            this.loaded--;
            this.update_dialog_cards_student_cards();
        });
        cancle.addEventListener("click", (event) => {
            close_dialog();
        });
        submit.addEventListener("click", (event) => {
            if(this.cached.length > 0) {
                let keys = Array(this.cached.length).fill("check_out_student");
                __send(keys, this.cached);
            }
        });
    }

    generate_dialog_card_student_cards(student_container) {
        this.data.school_admin.students.forEach(students => {
            let student = document.createElement("ul");
            student_container.appendChild(student);

            this.generate_dialog_card_student_skill(student, students);

            student.addEventListener("click", (event) => {
                if(this.cached.includes(students.id)) {
                    this.cached.splice(this.cached.indexOf(students.id), 1);
                    student.classList.remove("active-student");
                } else {
                    this.cached.push(students.id);
                    student.classList.add("active-student");
                }
            });
        });
    }

    generate_dialog_card_student_skill(student, students) {
        students.skills.forEach(skills => {
            let skill = document.createElement("li");
            let span = document.createElement("span");
            let container = document.createElement("div");

            student.appendChild(skill);
            skill.appendChild(span);
            skill.appendChild(container);

            span.innerText = skills.name;

            // base skill
            for(let index = 0; index < 5; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.disabled = true;
                checkbox.checked = index == skills.value;
            }

            // teacher skill (obisously never checked)
            for(let index = 0; index < 2; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.disabled = true;
                checkbox.checked = false;
            }
        });
    }

    update_dialog_cards_student_cards() {
        let student_container = document.getElementById("dialog-student-student");
        let header = document.getElementById("dialog-student-header");
        let load_values = 0;

        for(let i = 0; i < student_container.childElementCount; i++) {
            student_container.children[i].classList.remove("loaded-student");
        }
        if(student_container.childElementCount > 0) {
            student_container.children[this.loaded].classList.add("loaded-student");
            load_values = this.loaded + 1;
        }

        header.innerText = `Schüler auschecken ${load_values}/${this.data.school_admin.students.length}`;
    }

    open_dialog() {
        // set the loaded children to default
        this.loaded = 0;
        this.update_dialog_cards_student_cards();
        // make the correct child visible
        open_dialog('dialog-student');
    }
}

class Teachers
{
    constructor(data, team_id) {
        this.data = data;
        console.log(this.data);

        this.cached_changes_base = [];
        this.cached_changes_advanced = [];

        this.generate_panels();
        this.generate_dialog_cards();
    }

    generate_panels() {
        this.data.school_admin.teachers.forEach(teachers => {
            let teacher = document.createElement("div");
            let header = document.createElement("h4");
            let edit_button = document.createElement("button");
            let edit_image = document.createElement("img");
            let paragraph_name = document.createElement("p");
            let paragraph_skill = document.createElement("p");
            let span_advanved = document.createElement("span");
            let span_base = document.createElement("span");
            let image_advanced = document.createElement("img");
            let image_base = document.createElement("img");
            let span_advanced_value = document.createElement("span");
            let span_base_value = document.createElement("span");

            teacher_container.appendChild(teacher);

            teacher.appendChild(header);
            teacher.appendChild(paragraph_name);
            teacher.appendChild(paragraph_skill);

            teacher.classList.add("panel");

            paragraph_skill.appendChild(span_advanved);
            paragraph_skill.appendChild(span_base);

            header.innerText = teachers.name;
            paragraph_name.innerText = teachers.name;

            header.appendChild(edit_button);
            edit_button.appendChild(edit_image);

            paragraph_skill.style["justifyContent"] = "space-around";

            edit_image.src = "../../../../assets/imgs/edit.svg";
            image_advanced.src = "../../../../assets/imgs/education.svg";
            image_base.src = "../../../../assets/imgs/star.svg";

            span_advanved.appendChild(image_advanced);
            span_advanved.appendChild(span_advanced_value);
            span_base.appendChild(image_base);
            span_base.appendChild(span_base_value);

            span_advanced_value.innerText = this.get_subj_specfic_advanced(teachers.name, teachers.skills) + 1;
            span_base_value.innerText = this.get_subj_specfic_base(teachers.name, teachers.skills) + 1;

            edit_button.addEventListener("click", (event) => {
                open_dialog(`dialog-teacher-${teachers.name}`);
            });
        });
    }

    generate_dialog_cards() {
        this.data.school_admin.teachers.forEach(teachers => {
            let dialog_card = document.createElement("div");
            let header = document.createElement("h1");
            let teacher_container = document.createElement("div");
            let button_container = document.createElement("div");
            let cancle = document.createElement("button");
            let submit = document.createElement("button");

            dialog.appendChild(dialog_card);

            dialog_card.appendChild(header);
            dialog_card.appendChild(teacher_container);
            dialog_card.appendChild(button_container);

            this.generate_dialog_card_teacher_card(teacher_container, teachers);

            button_container.appendChild(cancle);
            button_container.appendChild(submit);

            dialog_card.id = `dialog-teacher-${teachers.name}`;
            dialog_card.classList.add("dialog-teacher");
            header.innerText = `${teachers.name} Lehrer`;
            button_container.classList.add("button-container");

            cancle.innerText = "Schließen";
            submit.innerText = "Aktualisieren";
            submit.style.background = "var(--colour-red)";

            cancle.addEventListener("click", (event) => {
                close_dialog();
                // resets all the values to original
                this.reset_values(teachers, teacher_container);
                // reset caches
                this.cached_changes_base = [];
                this.cached_changes_advanced = [];
            });
            submit.addEventListener("click", (event) => {
                let keys = []
                keys.push(...Array(this.cached_changes_base.length).fill("set_teacher_base"));
                keys.push(...Array(this.cached_changes_advanced.length).fill("set_teacher_advanced"));

                let values = [];
                values.push(...this.cached_changes_base);
                values.push(...this.cached_changes_advanced);

                __send(keys, values);
            });
        });
    }

    generate_dialog_card_teacher_card(teacher_container, teachers) {
        let teacher = document.createElement("ul");
        teacher_container.appendChild(teacher);

        for(let skill_index = 0; skill_index < teachers.skills.length; skill_index++) {
            let skill = document.createElement("li");
            let span = document.createElement("span");
            let container = document.createElement("div");

            teacher.appendChild(skill);
            skill.appendChild(span);
            skill.appendChild(container);

            span.innerText = teachers.skills[skill_index].name;

            // base skill
            for(let index = 0; index < 5; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = index == teachers.skills[skill_index].base;

                checkbox.addEventListener("click", (event) => {
                    for(let children_index = 0; children_index < 5; children_index++) {
                        checkbox.parentNode.children[children_index].checked = index == children_index;
                    }
                    for(let i = 0; i < this.cached_changes_base.length; i++) {
                        let array = this.cached_changes_base[i].split(";");

                        if(array[0] == teachers.name && array[1] == skill_index) {
                            this.cached_changes_base.splice(i, 1);
                        }
                    }
                    this.cached_changes_base.push(`${teachers.name};${skill_index};${index}`);

                    console.log(`${teachers.name};${skill_index};${index}`);
                })
            }

            // advanced skill
            for(let index = 0; index < 2; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = index == teachers.skills[skill_index].advanced;

                checkbox.addEventListener("click", (event) => {
                    for(let children_index = 5; children_index < 7; children_index++) {
                        checkbox.parentNode.children[children_index].checked = (5 + index) == children_index;
                    }
                    for(let i = 0; i < this.cached_changes_advanced.length; i++) {
                        let array = this.cached_changes_advanced[i].split(";");

                        if(array[0] == teachers.name && array[1] == skill_index) {
                            this.cached_changes_advanced.splice(i, 1);
                        }
                    }
                    this.cached_changes_advanced.push(`${teachers.name};${skill_index};${index}`);

                    console.log(this.cached_changes_advanced);
                })
            }
        }
    }

    get_subj_specfic_advanced(name, skills) {
        let advanced;

        skills.forEach(skill => {
            if(skill.name === name) {
                advanced = skill.advanced;
            }
        });

        return advanced;
    }

    get_subj_specfic_base(name, skills) {
        let base;

        skills.forEach(skill => {
            if(skill.name === name) {
                base = skill.base;
            }
        });

        return base;
    }

    reset_values(teachers, teacher_container) {
        for(let index = 0; index < teachers.skills.length; index++) {
            let inputs = teacher_container.children[0].children[index].children[1].children;
            // base
            for(let i = 0; i < 5; i++) {
                inputs[i].checked = i == teachers.skills[index].base;
            }
            // advanced
            for(let j = 0; j < 2; j++) {
                inputs[j + 5].checked = j == teachers.skills[index].advanced;
            }
        }
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
    teacher_container = document.getElementById("teacher-slot");

    new General(data, team_id);
    students = new Students(data, team_id);
    new Teachers(data, team_id);
    new Buildings(data, team_id);
}

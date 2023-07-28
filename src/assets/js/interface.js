// === GLOBAL VARIABLES ===
let team_drawer;
let teamname;
let building_tree;
let not_fetched;
let teacher_container;

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
        let section = document.createElement("section");
        let cancle = document.createElement("button");
        let submit = document.createElement("button");

        dialog.appendChild(dialog_card);

        dialog_card.appendChild(header);
        dialog_card.appendChild(paragraph);
        dialog_card.appendChild(text_inp);
        dialog_card.appendChild(section);

        section.appendChild(cancle);
        section.appendChild(submit);

        dialog_card.id = "dialog-general-name";

        header.innerText = "Team Name ändern";
        paragraph.innerText = `Ändere den Team Namen hier und auf der vorliegenden Team Zettel.
                                Teams sollten nicht ihrer Zeit verschwenden ihren Namen die ganze Zeit zu ändern.`;

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
}

class Buildings
{
    constructor(data, team_id) {
        this.data = data;

        this.generate_building_tree();
        this.generate_dialog_cards();
    }

    generate_building_tree() {
        this.data.school_admin.buildings.forEach(main_branches => {
            let main_branch = document.createElement("div");
            building_tree.appendChild(main_branch);

            main_branches.forEach(levels => {
                let level = document.createElement("div");
                main_branch.appendChild(level);

                levels.forEach(buildings => {
                    let building_status = this.get_building_tree_building_status(buildings)

                    let building = document.createElement("button");
                    level.appendChild(building);

                    building.classList.add(building_status);
                    building.innerText = buildings.name;
                    building.disabled = !buildings.parent_active; // not parent active

                    building.addEventListener("click", (event) =>  {
                        open_dialog(`dialog-buildings-${buildings.id}`);
                    });
                });
            });
        });
    }

    get_building_tree_building_status(building) {
        return building.active ? "active_building" : "inactive_building";
    }

    generate_dialog_cards() {
        this.data.school_admin.buildings.forEach(main_branches => {
            main_branches.forEach(levels => {
                levels.forEach(buildings => {
                    let dialog_card = document.createElement("div");
                    let header = document.createElement("h1");
                    let requriments_header = document.createElement("h4");
                    let requriments = document.createElement("ul");
                    let yields_header = document.createElement("h4");
                    let yields = document.createElement("ul");
                    let section = document.createElement("section");
                    let cancle = document.createElement("button");
                    let submit = document.createElement("button");

                    dialog.appendChild(dialog_card);

                    dialog_card.appendChild(header);
                    dialog_card.appendChild(requriments_header);
                    dialog_card.appendChild(requriments);
                    dialog_card.appendChild(yields_header);
                    dialog_card.appendChild(yields);
                    dialog_card.appendChild(section);

                    this.generate_dialog_card_list(requriments, buildings.requriments);
                    this.generate_dialog_card_list(yields, buildings.yields);

                    section.appendChild(cancle);
                    section.appendChild(submit);

                    dialog_card.id = `dialog-buildings-${buildings.id}`;
                    header.innerText = `${buildings.name.replace("-", "")}`;
                    requriments_header.innerText = "Benötigt:";
                    yields_header.innerText = "Erträge:";

                    cancle.innerText = "Schließen";
                    submit.innerText = this.get_dialog_card_submit_name(buildings);
                    submit.style.background = `var(--colour-${this.get_dialog_card_submit_colour(buildings)})`;

                    cancle.addEventListener("click", (event) => {
                        close_dialog();
                    })
                    submit.addEventListener("click", (event) => {
                        // aquire callback
                        let callback = this.get_dialog_card_callback(buildings);
                        // send data (acitvate_building_id/deacitvate_building_id -> buildings.id) to backend
                        __send([callback], [buildings.id]);
                    })
                });
            });
        });
    }

    generate_dialog_card_list(html_element, array) {
        array.forEach(elements => {
            let element = document.createElement("li");
            element.innerText = elements;
            html_element.appendChild(element);
        });
    }

    get_dialog_card_submit_name(building) {
        return building.active ? "Deaktivieren" : "Aktivieren";
    }

    get_dialog_card_submit_colour(building) {
        return building.active ? "red" : "green";
    }

    get_dialog_card_callback(building) {
        return building.active ? "deacitvate_building_id" : "acitvate_building_id";
    }
}

class Students
{
    constructor(data, team_id) {
        this.data = data;

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
        let student_container = document.createElement("div");
        let section = document.createElement("section");
        let cancle = document.createElement("button");
        let submit = document.createElement("button");

        dialog.appendChild(dialog_card);

        dialog_card.appendChild(header);
        dialog_card.appendChild(student_container);
        dialog_card.appendChild(section);

        this.generate_dialog_card_student_cards(student_container);

        section.appendChild(cancle);
        section.appendChild(submit);

        dialog_card.id = "dialog-student";
        header.innerText = "Schüler auschecken";

        cancle.innerText = "Schließen";
        submit.innerText = "Ausschecken";
        submit.style.background = "var(--colour-green)";

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
                    student.classList.remove("student-clicked");
                } else {
                    this.cached.push(students.id);
                    student.classList.add("student-clicked");
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
}

class Teachers
{
    constructor(data, team_id) {
        this.data = data;

        this.cached_changes = [];

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
            let section = document.createElement("section");
            let cancle = document.createElement("button");
            let submit = document.createElement("button");

            dialog.appendChild(dialog_card);

            dialog_card.appendChild(header);
            dialog_card.appendChild(teacher_container);
            dialog_card.appendChild(section);

            this.generate_dialog_card_teacher_card(teacher_container, teachers);

            section.appendChild(cancle);
            section.appendChild(submit);

            dialog_card.id = `dialog-teacher-${teachers.name}`;
            header.innerText = teachers.name;

            cancle.innerText = "Schließen";
            submit.innerText = "Aktualisieren";
            submit.style.background = "var(--colour-red)";

            cancle.addEventListener("click", (event) => {
                close_dialog();
                // resets all the values to original
                this.reset_values(teachers, teacher_container);
            });
            submit.addEventListener("click", (event) => {

            });
        });
    }

    generate_dialog_card_teacher_card(teacher_container, teachers) {
        let teacher = document.createElement("ul");
        teacher_container.appendChild(teacher);

        teachers.skills.forEach(skills => {
            let skill = document.createElement("li");
            let span = document.createElement("span");
            let container = document.createElement("div");

            teacher.appendChild(skill);
            skill.appendChild(span);
            skill.appendChild(container);

            span.innerText = skills.name;

            // base skill
            for(let index = 0; index < 5; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = index == skills.base;

                checkbox.addEventListener("click", (event) => {
                    for(let children_index = 0; children_index < 5; children_index++) {
                        checkbox.parentNode.children[children_index].checked = index == children_index;
                    }

                    // TODO: cache changes
                })
            }

            // advanced skill
            for(let index = 0; index < 2; index++) {
                let checkbox = document.createElement("input");
                container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = index == skills.advanced;

                checkbox.addEventListener("click", (event) => {
                    for(let children_index = 5; children_index < 7; children_index++) {
                        checkbox.parentNode.children[children_index].checked = 5 + index == children_index;
                    }
                })
            }
        });
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
    // students
    not_fetched = document.getElementById("students-not-fetched");
    // teachers
    teacher_container = document.getElementById("teachers");

    new General(data, team_id);
    new Buildings(data, team_id);
    new Students(data, team_id);
    new Teachers(data, team_id);
}

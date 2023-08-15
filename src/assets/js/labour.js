let team_drawer;
let teamname;
let prestige_accumulated;
let job_slot;

// === ROUTEES TEAM ===
function route_team() {
    // routing to the correct Team location
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

class Prestige
{
    constructor(data, team_id) {
        this.data = data;

        this.generate_prestige_label();
        this.generate_dialog_card();
    }

    generate_prestige_label() {
        prestige_accumulated.innerText = this.data.labour.prestige;
    }

    generate_dialog_card() {
        let name = "prestige";
        let id = 0;

        let dialog_card = new DialogCard(name, id);
        let paragraph = document.createElement("p");
        let text_input = document.createElement("input");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(paragraph);
        dialog_card.container.appendChild(text_input);

        dialog_card.header.innerText = "Prestige hinzufügen";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Ändern";

        text_input.type = "number";
        text_input.placeholder = "Prestige hinzufügen";
        text_input.value = "";

        paragraph.innerText = "Füge prestige durch Prestige Kärtchen hinzu.";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
            text_input.value = "";
        })
        dialog_card.submit.addEventListener("click", (event) => {
            // aquire the new teamname and remove blank spaces
            if(Number.isInteger(parseInt(text_input.value.trim()))) {
                __send(["prestige_add"], [parseInt(text_input.value.trim())]);
            }
        });
    }
}

class Labour
{
    constructor(data, team_id) {
        this.data = data;

        this.generate_panels();
        this.generate_dialog_cards();
        this.generate_adding_dialog_cards();
        this.generate_editing_dialog_cards();
        this.generate_deleting_dialog_cards();
        this.generate_influence_dialog_cards();
    }

    generate_panels() {
        this.data.labour.jobs.forEach(job => {
            let job_panel = new PanelCard(job.name);
            job_slot.appendChild(job_panel.generate());

            let image_source = "../../../../assets/imgs/percent.svg";
            let value = `${Math.round(job.influence * 100 * 10000) / 10000}%`;

            let influence_display = job_panel.add_display(value, image_source);

            job_panel.action_button.addEventListener("click", (event) => {
                open_dialog(`dialog-jobs-${job.name}`);
            });

            influence_display.addEventListener("click", (event) => {
                open_dialog(`dialog-influence-${job.name}`);
            });
        });
    }

    generate_dialog_cards() {
        this.data.labour.jobs.forEach(job => {
            let dialog_card = new DialogCard("jobs", job.name);
            let worker_list = this.generate_worker_list(job);

            dialog.appendChild(dialog_card.generate());
            dialog_card.container.appendChild(worker_list);

            dialog_card.header.innerText = `${job.name} Berufsbild`;
            dialog_card.cancel.innerText = "Schließen";
            dialog_card.submit.innerText = "Hinzufügen";

            dialog_card.cancel.addEventListener("click", (event) => {
                close_dialog();
            });
            dialog_card.submit.addEventListener("click", (event) => {
                close_dialog();
                open_dialog(`dialog-worker-adding-${job.name}`);
            });
        });
    }

    generate_worker_list(job) {
        let worker_list = document.createElement("ul");
        worker_list.classList.add("list");
        worker_list.classList.add("noselect");

        for(let worker_index = job.workers.length - 1; worker_index >= 0; worker_index--) {
            let worker = job.workers[worker_index];

            let worker_list_element = document.createElement("li");
            let worker_list_id = document.createElement("span");
            let worker_list_value = document.createElement("span");
            let worker_list_delete = document.createElement("button");
            let worker_delete_image = document.createElement("img");

            worker_list.appendChild(worker_list_element);
            worker_list_element.appendChild(worker_list_id);
            worker_list_element.appendChild(worker_list_value);
            worker_list_element.appendChild(worker_list_delete);
            worker_list_delete.appendChild(worker_delete_image);

            worker_list_id.innerText = `#${worker_index + 1}`;
            worker_delete_image.src = "../../../../assets/imgs/delete.svg";

            worker.skills.forEach(skill => {
                let skill_container = document.createElement("span");
                let skill_name = skill.name.replace(/[aeiouäöü]/gi, '').substring(0, 5);
                let skill_base = skill.base;

                skill_container.innerText = `${skill_name}: ${skill_base}`;

                worker_list_value.appendChild(skill_container);
            });

            worker_list_element.addEventListener("click", (event) => {
                if(!clicked_element(worker_list_delete, event.target)) {
                    close_dialog();
                    open_dialog(`dialog-worker-editing-${job.name}-${worker_index}`);
                }
            });

            worker_list_delete.addEventListener("click", (event) => {
                close_dialog();
                open_dialog(`dialog-worker-deleting-${job.name}-${worker_index}`);
            });
        }

        return worker_list;
    }

    generate_adding_dialog_cards() {
        this.data.labour.jobs.forEach(job => {
            let dialog_card = new DialogCard("worker-adding", job.name);
            let worker_card = new SkillCard(this.data.general.skills);

            dialog.appendChild(dialog_card.generate());
            dialog_card.container.appendChild(worker_card.generate(false, true));

            dialog_card.header.innerText = `Arbeiter für ${job.name} hinzufügen`;
            dialog_card.cancel.innerText = "Abbrechen";
            dialog_card.submit.innerText = "Hinzufügen";

            dialog_card.cancel.addEventListener("click", (event) => {
                // dialog
                close_dialog();
                open_dialog(`dialog-jobs-${job.name}`);
                // reset
                worker_card.reset();
            });
            dialog_card.submit.addEventListener("click", (event) => {
                let keys = ["labour_add_worker"];
                let values = [job.name];

                worker_card.base_cached_changes.forEach(struct => {
                    keys.push("labour_add_base");
                    values.push(struct);
                });

                worker_card.advanced_cached_changes.forEach(struct => {
                    keys.push("labour_add_advanced");
                    values.push(struct);
                });

                __send(keys, values);
            });
        });
    }

    generate_editing_dialog_cards() {
        this.data.labour.jobs.forEach(job => {
            for(let worker_index = 0; worker_index < job.workers.length; worker_index++) {
                let worker = job.workers[worker_index];

                let dialog_card = new DialogCard("worker-editing", `${job.name}-${worker_index}`);
                let worker_card = new SkillCard(worker.skills);

                dialog.appendChild(dialog_card.generate());
                dialog_card.container.appendChild(worker_card.generate(false, true));

                dialog_card.header.innerText = `Ändere ${job.name}-#${worker_index+1}`;
                dialog_card.cancel.innerText = "Zurück";
                dialog_card.submit.innerText = "Ändern";

                dialog_card.cancel.addEventListener("click", (event) => {
                    // dialog
                    close_dialog();
                    open_dialog(`dialog-jobs-${job.name}`);
                    // reset
                    worker_card.reset();
                });
                dialog_card.submit.addEventListener("click", (event) => {
                    let keys = Array();   // labour_set_base / labour_set_advanced
                    let values = Array(); // job.name;worker_index;skill_index;value

                    worker_card.base_cached_changes.forEach(struct => {
                        keys.push("labour_set_base");
                        values.push(`${worker.id};${struct}`);
                    });

                    worker_card.advanced_cached_changes.forEach(struct => {
                        keys.push("labour_set_advanced");
                        values.push(`${worker.id};${struct}`);
                    });

                    __send(keys, values);
                });
            }
        });
    }

    generate_deleting_dialog_cards() {
        this.data.labour.jobs.forEach(job => {
            for(let worker_index = 0; worker_index < job.workers.length; worker_index++) {
                let worker = job.workers[worker_index];

                let dialog_card = new DialogCard("worker-deleting", `${job.name}-${worker_index}`);
                let worker_card = new SkillCard(worker.skills);

                dialog.appendChild(dialog_card.generate());
                dialog_card.container.appendChild(worker_card.generate(true, true));

                dialog_card.header.innerText = `Entferne ${job.name}-#${worker_index+1}`;
                dialog_card.cancel.innerText = "Zurück";
                dialog_card.submit.innerText = "Entfernen";
                dialog_card.submit.style.backgroundColor = "var(--colour-red)";

                dialog_card.cancel.addEventListener("click", (event) => {
                    // dialog
                    close_dialog();
                    open_dialog(`dialog-jobs-${job.name}`);
                    // reset
                    worker_card.reset();
                });
                dialog_card.submit.addEventListener("click", (event) => {
                    // send deleting information
                    __send(["labour_delete_worker"], [worker.id]);
                });
            }
        });
    }

    generate_influence_dialog_cards() {
        this.data.labour.jobs.forEach(job => {
            let dialog_card = new DialogCard("influence", job.name);
            let paragraph = document.createElement("p");
            let text_input = document.createElement("input");

            dialog.appendChild(dialog_card.generate());

            dialog_card.container.appendChild(paragraph);
            dialog_card.container.appendChild(text_input);

            paragraph.innerText = `Ändern des Einflusses eines Teams hat zur auswirkung, dass die fehlenden Einfluss Punkte für den angepeilten Prozentualen Einfluss zum Team-Job-Einfluss Wert hinzugefügt wird.
                Andere Teams werden daher Prozentual verlieren`;

            text_input.type = "number";
            text_input.placeholder = `${Math.round(job.influence * 100 * 10000) / 10000}%`;
            text_input.value = `${Math.round(job.influence * 100 * 10000) / 10000}`;

            dialog_card.header.innerText = `Ändere Einfluss in Job ${job.name}`;
            dialog_card.cancel.innerText = "Abbrechen";
            dialog_card.submit.innerText = "Ändern";

            dialog_card.cancel.addEventListener("click", (event) => {
                close_dialog();
            });
            dialog_card.submit.addEventListener("click", (event) => {
                if(Number.isInteger(parseInt(text_input.value.trim()))) {
                    __send(["labour_change_influence"], [`${job.name};${text_input.value / 100}`]);
                }
            });
        });
    }
}

function initialize(data, team_id) {
    // team
    team_drawer = document.getElementById("team-drawer");
    teamname = document.getElementById("teamname");
    // prestige
    prestige_accumulated = document.getElementById("prestige-accumulated");
    // jobs
    job_slot = document.getElementById("job-slot");

    let general = new General(data, team_id);
    new Prestige(data, team_id);
    new Labour(data, team_id);

    general.generate_team_drawer();
    general.generate_teamname();
    general.generate_time_interval();
}

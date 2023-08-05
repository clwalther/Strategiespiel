class Navigate
{
    constructor(data, team_id) {
        this.data = data;

        this.cached_backup = undefined;
    }

    generate_start_dialog_card() {
        let dialog_card = new DialogCard("start", 0);
        let paragraph = document.createElement("p");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(paragraph);

        dialog_card.header.innerText = "Starte das Spiel";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Starten / Fortsetzen";

        paragraph.innerText = "Hier startet man das spiel oder lässt es weiterlaufen wenn es schon mal lief.";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
        })
        dialog_card.submit.addEventListener("click", (event) => {
            __send(["general_start"], [true]);
        });
    }

    generate_stop_dialog_card() {
        let dialog_card = new DialogCard("stop", 0);
        let paragraph = document.createElement("p");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(paragraph);

        dialog_card.header.innerText = "Pausieren";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Pause";

        paragraph.innerText = "Pausiere das Spiel. Während dieser Zeit werden Zeit Intervalle nicht fortgesetzt und entsprechend pausiert.";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
        })
        dialog_card.submit.addEventListener("click", (event) => {
            __send(["general_pause"], [true]);
        });
    }

    generate_reset_dialog_card() {
        let dialog_card = new DialogCard("reset", 0);
        let paragraph = document.createElement("p");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(paragraph);

        dialog_card.header.innerText = "Reset";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Zurück Setzen";
        dialog_card.submit.style.backgroundColor = "var(--colour-red)";

        paragraph.innerText = "Setze Sie die Spieleinstellungen hier vollständig zurück. ACHTUNG der ganze Progress wird gelöscht und ist nur noch durch Backups wiederherzustellen, insofern Backups existieren!";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
        })
        dialog_card.submit.addEventListener("click", (event) => {
            __send(["general_reset"], [true]);
        });
    }

    generate_backup_dialog_card() {
        let dialog_card = new DialogCard("backup", 0);
        let paragraph = document.createElement("p");

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(paragraph);

        dialog_card.header.innerText = "Backup";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Backup";

        paragraph.innerText = "Diese Aktion macht ein Backup des momentaen Spielstandes und ist niemals schlecht. Sie sollten jedoch wissen das diese Aktion Zeit geloggt wird und einen bestimmten Tag bekommt an dem man erkennen kann das diese Backup nicht automatisch asugeführt wurde.";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
        })
        dialog_card.submit.addEventListener("click", (event) => {
            __send(["general_backup"], [true]);
        });
    }

    generate_load_backup_dialog_card() {
        let dialog_card = new DialogCard("load-backup", 0);
        let backup_list = this.generate_backup_list();

        dialog.appendChild(dialog_card.generate());
        dialog_card.container.appendChild(backup_list);

        dialog_card.header.innerText = "Lade Backup";
        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Laden";

        dialog_card.cancel.addEventListener("click", (event) => {
            close_dialog();
            // reset
            this.cached_backup = undefined;
        })
        dialog_card.submit.addEventListener("click", (event) => {
            if(this.cached_backup != undefined) {
                __send(["general_load_backup"], [this.cached_backup])
            }
        });
    }

    generate_backup_list() {
        let backup_list = document.createElement("ul");
        backup_list.classList.add("list");
        backup_list.classList.add("noselect");

        for(let backup_index = this.data.general.backups.length - 1; backup_index >= 0; backup_index--) {
            let backup_name = this.data.general.backups[backup_index];

            let backup_list_element = document.createElement("li");
            let backup_list_id = document.createElement("span");
            let backup_list_name = document.createElement("span");
            let backup_list_image = document.createElement("img");

            backup_list.appendChild(backup_list_element);
            backup_list_element.appendChild(backup_list_id);
            backup_list_element.appendChild(backup_list_name);
            backup_list_element.appendChild(backup_list_image);

            backup_list_id.innerText = `#${backup_index + 1}`;
            backup_list_name.innerText = backup_name;
            backup_list_image.src = "../../../../assets/imgs/protect.svg";

            backup_list_element.addEventListener("click", (event) => {
                this.cached_backup = backup_name;

                for(let list_index = 0; list_index < backup_list_element.parentNode.childElementCount; list_index++) {
                    backup_list_element.parentNode.children[list_index].classList.remove("enabled-list-element");
                }

                backup_list_element.classList.add("enabled-list-element");
            });
        }

        return backup_list;
    }
}


function initialize(data, team_id) {
    let general = new General(data, 1);
    let navigate = new Navigate(data, 1);

    general.generate_time_interval();

    navigate.generate_start_dialog_card();
    navigate.generate_stop_dialog_card();
    navigate.generate_reset_dialog_card();
    navigate.generate_backup_dialog_card();
    navigate.generate_load_backup_dialog_card();
}

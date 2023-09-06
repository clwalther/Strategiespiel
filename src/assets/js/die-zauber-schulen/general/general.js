class General
{
    constructor(data, team_id) {
        this.data = data;
        this.team_id = team_id;
        this.time_loaded = Date.now();

        this.teamname = this.data.general.teams[this.team_id - 1].teamname;
    }

    generate_team_drawer() {
        // go through every team
        this.data.general.teams.forEach(team => {
            // generate button element
            let status = team.group_id == this.team_id ? "active_button" : "inactive_button";

            let button = document.createElement("button");
            let image = document.createElement("img");
            let span = document.createElement("span");

            team_drawer.appendChild(button);
            button.appendChild(image);
            button.appendChild(span);

            button.classList.add(status);

            image.src = "../../../../assets/icons/group.svg";
            span.innerText = `Team ${team.group_id}  -  ${team.teamname}`;

            button.addEventListener("click", (event) => {
                window.open(`./index.php?Team=${team.group_id}`, '_self');
            });
        });
    }

    generate_teamname() {
        teamname.innerText = `Team ${this.team_id} - ${this.teamname}`;
    }

    generate_dialog_card() {
        let dialog_card = new DialogCard("general", "name");
        let paragraph = document.createElement("p");
        let text_input = document.createElement("input");

        dialog.appendChild(dialog_card.generate());

        dialog_card.container.appendChild(paragraph);
        dialog_card.container.appendChild(text_input);

        dialog_card.header.innerText = "Team Name ändern";
        paragraph.innerText = `Ändere den Team Namen hier und auf der vorliegenden Team Zettel.
                                Teams sollten nicht ihrer Zeit verschwenden ihren Namen die ganze Zeit zu ändern.`;

        text_input.type = "text";
        text_input.placeholder = "Team Name";
        text_input.value = this.teamname;

        dialog_card.cancel.innerText = "Schließen";
        dialog_card.submit.innerText = "Ändern";

        dialog_card.cancel.addEventListener("click", (event) => {
            // dialog
            close_dialog();
            // reset
            text_input.value = this.teamname;
        });

        dialog_card.submit.addEventListener("click", (event) => {
            // aquire the new teamname and remove blank spaces
            let new_teamname = text_input.value.trim();
            // check if teamname is valid
            if(new_teamname != "") {
                // send data (<team_id>;<teamname>) to backend
                __send(["general_change_name"], [`${this.team_id};${new_teamname}`]);
            }
        });
    }

    generate_time_interval() {
        if(this.data.general.times.is_running != null) {
            setInterval(function(times, now, loaded) {
                // compute ellapsed time
                let time_ellapsed = 0;

                for(let time_index = 0; time_index < times.length - 1; time_index++) {
                    if(times[time_index].type == 1) {
                        time_ellapsed += (times[time_index + 1].time - times[time_index].time) * 1000;
                    }
                }

                if(times[times.length - 1].type == 1) {
                    time_ellapsed += (now - times[times.length - 1].time) * 1000 + Date.now() - loaded;
                }

                // display
                let elements = document.getElementsByTagName("time");

                for(let index = 0; index < elements.length; index++) {
                    elements[index].innerText = new Date(time_ellapsed).toISOString().slice(11, -5);
                }
            }, 500, this.data.general.times.times, this.data.general.time_now, this.time_loaded);
        }
    }
}

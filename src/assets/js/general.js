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
            let status = team.group_id == this.team_id ? "active_button" : "inactive_button";

            let button = document.createElement("button");
            let image = document.createElement("img");
            let span = document.createElement("span");

            team_drawer.appendChild(button);
            button.appendChild(image);
            button.appendChild(span);

            button.classList.add(status);

            image.src = "../../../../assets/imgs/group.svg";
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
        if(this.data.general.times.is_running == 1) {
            setInterval(function () {
                let elements = document.getElementsByTagName("time");

                for(let index = 0; index < elements.length; index++) {
                    elements[index].innerText = "TODO: time";
                }
            }, 999);
        }
    }
}

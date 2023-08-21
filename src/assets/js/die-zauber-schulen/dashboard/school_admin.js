class School
{
    constructor(data, team_id) {
        this.data = data;
    }

    generate_school() {
        for (const group_id in this.data.buildings) {
            if (Object.hasOwnProperty.call(this.data.buildings, group_id)) {
                const team = this.data.buildings[group_id];

                let school_container = document.createElement("div");
                let parchment = document.createElement("img");
                let background = document.createElement("img");
                let base = document.createElement("img");

                body.appendChild(school_container);
                school_container.appendChild(parchment);
                school_container.appendChild(background);
                school_container.appendChild(base);

                this.append_buildings(group_id, team, school_container);

                school_container.id = team.group_id;
                parchment.src = "../../../assets/imgs/parchment.png"
                parchment.alt = "";

                background.src = "../../../assets/imgs/buildings/Hintergrund.png";
                background.alt = "";
                background.style.zIndex = 0;

                base.src = "../../../assets/imgs/buildings/Base.png";
                base.alt = "";
                background.style.zIndex = 0;


                parchment.classList.add("Parchment");
                background.classList.add("Hintergrund");
                base.classList.add("Base");
            }
        }
    }

    enabled_updates() {
        setInterval(async () => {
            let response = await fetch("/die-zauber-schulen/scripts/__get__.php?Team=undefined");
            this.data = await response.json();

            for (const group_id in this.data.buildings) {
                if (Object.hasOwnProperty.call(this.data.buildings, group_id)) {
                    const team = this.data.buildings[group_id];

                    this.update_buildings(group_id, team);
                }
            }

        }, 2 * 1000); // updating every 10 seconds
    }

    append_buildings(group_id, knot, container) {
        Object.keys(knot).forEach(building_name => {
            let building = knot[building_name];
            let building_img = document.createElement("img");

            building_img.src = `../../../assets/imgs/buildings/${building_name}.png`;
            building_img.alt = "";
            building_img.id = `${group_id}-${building_name}`;
            building_img.style.display = building.active ? "block" : "none";

            building_img.classList.add(building_name);

            container.appendChild(building_img);

            if(building.children != "none") {
                this.append_buildings(group_id, building.children, container);
            }
        });
    }

    update_buildings(group_id, knot) {
        Object.keys(knot).forEach(building_name => {
            let building = knot[building_name];
            let building_img = document.getElementById(`${group_id}-${building_name}`);

            building_img.style.display = building.active ? "block" : "none";

            if(building.children != "none") {
                this.update_buildings(group_id, building.children);
            }
        });
    }
}

function initialize(data, team_id) {
    let school = new School(data, team_id);

    school.generate_school();
    school.enabled_updates();
}

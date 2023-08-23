class School
{
    constructor(data, team_id) {
        this.data = data;

        this.buildings = Array();
        this.access_buildings = 0;
    }

    generate_school() {
        for (const group_id in this.data.buildings) {
            if (Object.hasOwnProperty.call(this.data.buildings, group_id)) {
                const team = this.data.buildings[group_id];
                const teamname = this.data.general.teams[group_id - 1].teamname;

                let group_container = document.createElement("div");
                let school_container = document.createElement("div");
                let background = document.createElement("img");
                let base = document.createElement("img");

                let header = document.createElement("header");
                let title = document.createElement("h1");
                let banner = document.createElement("img");

                let border_top = document.createElement("div");
                let border_left = document.createElement("div");
                let border_right = document.createElement("div");
                let border_bottom = document.createElement("div");

                let border_top_img_left = document.createElement("img");
                let border_top_img_right = document.createElement("img");
                let border_left_img_left = document.createElement("img");
                let border_left_img_right = document.createElement("img");
                let border_right_img_left = document.createElement("img");
                let border_right_img_right = document.createElement("img");
                let border_bottom_img_left = document.createElement("img");
                let border_bottom_img_right = document.createElement("img");

                this.buildings.push(group_container);

                body.appendChild(group_container);
                group_container.appendChild(header);
                group_container.append(school_container);

                school_container.appendChild(border_top);
                school_container.appendChild(border_left);
                school_container.appendChild(border_right);
                school_container.appendChild(border_bottom);

                school_container.appendChild(background);
                school_container.appendChild(base);

                header.appendChild(banner);
                header.appendChild(title);

                border_top.appendChild(border_top_img_left);
                border_top.appendChild(border_top_img_right);
                border_left.appendChild(border_left_img_left);
                border_left.appendChild(border_left_img_right);
                border_right.appendChild(border_right_img_left);
                border_right.appendChild(border_right_img_right);
                border_bottom.appendChild(border_bottom_img_left);
                border_bottom.appendChild(border_bottom_img_right);

                this.append_buildings(group_id, team, school_container);

                title.innerText = `Team ${group_id} - ${teamname}`;
                title.id = `${group_id}-title`;

                banner.src = "../../../assets/imgs/banner.png";
                banner.style.transform = "none";

                border_top.classList.add("border-top");
                border_left.classList.add("border-left");
                border_right.classList.add("border-right");
                border_bottom.classList.add("border-bottom");

                border_top_img_left.src = "../../../assets/imgs/border-edge.png";
                border_top_img_right.src = "../../../assets/imgs/border-edge.png";
                border_left_img_left.src = "../../../assets/imgs/border-edge.png";
                border_left_img_right.src = "../../../assets/imgs/border-edge.png";
                border_right_img_left.src = "../../../assets/imgs/border-edge.png";
                border_right_img_right.src = "../../../assets/imgs/border-edge.png";
                border_bottom_img_left.src = "../../../assets/imgs/border-edge.png";
                border_bottom_img_right.src = "../../../assets/imgs/border-edge.png";

                background.src = "../../../assets/imgs/buildings/Hintergrund.png";
                background.alt = "";
                background.style.zIndex = 0;

                base.src = "../../../assets/imgs/buildings/Base.png";
                base.alt = "";
                background.style.zIndex = 0;


                background.classList.add("Hintergrund");
                base.classList.add("Base");
            }
        }
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

    enabled_updates() {
        setInterval(async () => {
            let response = await fetch("/die-zauber-schulen/scripts/__get__.php?Team=undefined");
            this.data = await response.json();

            for (const group_id in this.data.buildings) {
                if (Object.hasOwnProperty.call(this.data.buildings, group_id)) {
                    const team = this.data.buildings[group_id];
                    const teamname = this.data.general.teams[group_id - 1].teamname;

                    document.getElementById(`${group_id}-title`).innerText = `Team ${group_id} - ${teamname}`;

                    this.update_buildings(group_id, team);
                }
            }

        }, 2 * 1000); // updating every 10 seconds
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

    change_buildings() {
        setInterval(() => {
            const displayed_buildings = 2;

            for (let building_index = -1; building_index <= displayed_buildings; building_index++) {
                let old_building = this.buildings[(this.access_buildings + building_index - 1) % this.buildings.length];
                let new_building = this.buildings[(this.access_buildings + building_index) % this.buildings.length];

                if(old_building != undefined) {
                    old_building.classList.remove(`active-schoool-display-${building_index}`);
                }
                if(new_building != undefined) {
                    new_building.classList.add(`active-schoool-display-${building_index}`);
                }
            }

            this.access_buildings++;
        }, 7 * 1000);
    }
}

function initialize(data, team_id) {
    let school = new School(data, team_id);

    school.generate_school();
    school.change_buildings();
    school.enabled_updates();
}

class School
{
    constructor(data, team_id) {
        this.data = data;

        this.buildings = Array();

        this.running = true;
        this.access_buildings = -1;
    }

    generate_school() {
        for (const group_id in this.data.buildings) {
            if (Object.hasOwnProperty.call(this.data.buildings, group_id)) {
                const team = this.data.buildings[group_id];
                const teamname = this.data.general.teams[group_id - 1].teamname;

                let group_container = document.createElement("div");
                let school_container = document.createElement("div");
                let info_container = document.createElement("div");
                let displacement_container = document.createElement("div");
                let background = document.createElement("img");
                let base = document.createElement("img");

                let header = document.createElement("header");
                let title = document.createElement("h1");
                let banner = document.createElement("img");
                let order_img = document.createElement("img");

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

                let border_top_displacement = document.createElement("img");
                let border_left_displacement = document.createElement("img");
                let border_right_displacement = document.createElement("img");
                let border_bottom_displacement = document.createElement("img");

                this.buildings.push(group_container);

                body.appendChild(group_container);
                body.appendChild(info_container);

                info_container.appendChild(header);
                info_container.appendChild(displacement_container);

                displacement_container.appendChild(border_top_displacement);
                displacement_container.appendChild(border_left_displacement);
                displacement_container.appendChild(border_right_displacement);
                displacement_container.appendChild(border_bottom_displacement);

                group_container.appendChild(school_container);
                group_container.appendChild(info_container);

                school_container.appendChild(border_top);
                school_container.appendChild(border_left);
                school_container.appendChild(border_right);
                school_container.appendChild(border_bottom);
                school_container.appendChild(order_img);

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
                this.append_gaussian_displacement(group_id, displacement_container);

                group_container.style.background = `url(../../../assets/imgs/order/background${Math.ceil(group_id / 4) - 1}.jpg) no-repeat center center fixed`;
                displacement_container.id = `displacement-${group_id}`;

                title.innerText = `Team ${group_id} - ${teamname}`;
                title.id = `${group_id}-title`;

                banner.src = "../../../assets/imgs/banner.png";
                order_img.src = `../../../assets/imgs/order/schulorden${Math.ceil(group_id / 4) - 1}.png`;
                order_img.classList.add("border-header");

                border_top.classList.add("border-top");
                border_left.classList.add("border-left");
                border_right.classList.add("border-right");
                border_bottom.classList.add("border-bottom");

                border_top_displacement.classList.add("border-dispalcement-top");
                border_left_displacement.classList.add("border-dispalcement-left");
                border_right_displacement.classList.add("border-dispalcement-right");
                border_bottom_displacement.classList.add("border-dispalcement-bottom");

                border_top_displacement.src = "../../../assets/imgs/simple-golden-border-horizontal.png";
                border_left_displacement.src = "../../../assets/imgs/simple-golden-border-vertical.png";
                border_right_displacement.src = "../../../assets/imgs/simple-golden-border-vertical.png";
                border_bottom_displacement.src = "../../../assets/imgs/simple-golden-border-horizontal.png";

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

    append_gaussian_displacement(group_id, html_container) {
        Object.keys(this.data.displacement[group_id]).forEach(subject_name => {
            let line = document.createElement("h3");
            let name = document.createElement("span");
            let value = document.createElement("span");

            html_container.appendChild(line);
            line.appendChild(name);
            line.appendChild(value);

            let ammount_of_half_stars = Math.round(this.data.displacement[group_id][subject_name] * 2);

            value.id = `displacement-${subject_name}-${group_id}`;

            for(var index = 0; index < 7 * 2; index++) {
                let half_star = document.createElement("img");
                value.appendChild(half_star);

                if (index < ammount_of_half_stars) {
                    half_star.src = "../../../assets/imgs/half_star_enabled.png";
                } else {
                    half_star.src = "../../../assets/imgs/half_star_disabled.png";
                }

                if(index % 2 == 0) {
                    half_star.style.transform = "scaleX(-1)";
                }
            }

            name.innerText = `${subject_name}:`;
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
                    this.update_displacements(group_id)
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

                let index = 0;


            }
        });
    }

    update_displacements(group_id) {
        Object.keys(this.data.displacement[group_id]).forEach(subject_name => {
            let ammount_of_half_stars = Math.round(this.data.displacement[group_id][subject_name] * 2);
            let html_container = document.getElementById(`displacement-${subject_name}-${group_id}`);

            for(var index = 0; index < 7 * 2; index++) {
                if (index < ammount_of_half_stars) {
                    html_container.children[index].src = "../../../assets/imgs/half_star_enabled.png";
                } else {
                    html_container.children[index].src = "../../../assets/imgs/half_star_disabled.png";
                }
            }

        });
    }

    change_buildings() {
        setInterval(() => {
            let delta = 1;

            if(this.running) {
                this.change_building(delta);
            }
        }, 7 * 1000);
    }

    change_building(delta) {
        const displayed_buildings = 1;

        this.access_buildings += delta;


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
    }
}

function initialize(data, team_id) {
    let school = new School(data, team_id);

    school.generate_school();
    school.change_buildings();
    school.enabled_updates();
    school.change_building(1);

    addEventListener("keydown", (event) => {
       switch (event.keyCode) {
            case 39:
                // arrow key right
                // shifts the displayed buildings to the right
                school.change_building(1);
                break;

            case 37:
                // arrow key left
                // shifts the displayed buildings to the left
                school.change_building(-1);
                break;

            case 32:
                // pauses and resums the diashow feature
                school.running = !school.running;
                break;

            default:
                // default ignore input
                break;
        }
    })
}

class Job
{
    constructor(data, team_id) {
        this.data = data;
    }

    generate_jobs() {
        const columns = 4;
        let job_index = 0;

        for (const job_name in this.data.influence) {
            if (Object.hasOwnProperty.call(this.data.influence, job_name)) {
                const job = this.data.influence[job_name];

                let job_containers = document.getElementsByClassName("rows");
                let container = document.createElement("div");
                let background_image = document.createElement("img");
                let influence_container = document.createElement("div");
                let description_container = document.createElement("div");
                let requirements_container = document.createElement("div");
                let header = document.createElement("h1");
                let sub_header = document.createElement("h2");
                let list = document.createElement("ol");

                job_containers[Math.floor(job_index / columns)].appendChild(container);
                container.appendChild(background_image);
                container.appendChild(influence_container);
                container.appendChild(description_container);
                container.appendChild(requirements_container);
                description_container.appendChild(header);
                description_container.appendChild(sub_header);
                requirements_container.appendChild(list);

                this.generate_pie_chart(influence_container);
                this.generate_requirement_list(list, job.requirements);

                background_image.src = "../../../assets/imgs/aged-paper.png";
                background_image.alt = "";

                influence_container.style.height = "calc(100% - 95px - 115px)";
                description_container.style.height = "95px";
                requirements_container.style.height = "115px";

                header.innerText = job_name.toUpperCase();
                sub_header.innerText = "MINISTERIUM FÜR ARBEIT";

                job_index++;
            }
        }
    }

    generate_requirement_list(list, requirements) {
        for (let requirement_index = 0; requirement_index < requirements.length; requirement_index++) {
            const requirement_value = requirements[requirement_index];

            let requirement = document.createElement("li");

            if(requirement_index != requirements.length - 1) {
                requirement.innerText = requirement_value;
            } else {
                requirement.classList.add("jibberish");
            }

            list.appendChild(requirement);
        }
    }

    enabled_updates() {
        setInterval(async () => {
            let response = await fetch("/die-zauber-schulen/scripts/__get__.php?Team=undefined");
            this.data = await response.json();

        }, 2 * 1000); // updating every 10 seconds
    }

    generate_pie_chart(chart_housing) {

    }

    generate_jibberish() {
        const elements = document.getElementsByClassName("jibberish");

        setInterval(() => {
            for (let element_index = 0; element_index < elements.length; element_index++) {
                let element = elements[element_index];

                element.innerHTML = "";

                for (let char_index = 0; char_index < Math.floor(Math.random() * 10) + 10; char_index++) {
                    element.innerHTML += `&#${Math.floor(Math.random() * 200)};`;
                }
            }
        }, 100);
    }
}

function initialize(data, team_id) {
    let job = new Job(data, team_id);

    console.log(data);
    job.generate_jobs();
    job.enabled_updates();
    job.generate_jibberish();
}

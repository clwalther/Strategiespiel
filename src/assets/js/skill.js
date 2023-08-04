class SkillCard
{
    constructor(skills) {
        this.card;
        this.skills = skills;

        this.base_cached_changes = Array();
        this.advanced_cached_changes = Array();

        this.max_base_skill = 5;
        this.max_advanced_skill = 7;
    }

    generate(base_disabled = false, advanced_disabled = false) {
        /* This function generates the a skill card html element object that will beused to display the
        skills of an idividual (student/teacher/worker) */
        this.card = document.createElement("ul");

        for(let skill_index = 0; skill_index < this.skills.length; skill_index++) {
            let skill = this.skills[skill_index];

            let skill_container = document.createElement("li");
            let skill_name = document.createElement("span");
            let checkbox_container = document.createElement("div");

            this.card.appendChild(skill_container);
            skill_container.appendChild(skill_name);
            skill_container.appendChild(checkbox_container);

            this.card.classList.add("skill-card");
            skill_name.innerText = skill.name;

            // generate base-value checkboxes to: 5
            for(let checkbox_index = 0; checkbox_index < this.max_base_skill; checkbox_index++) {
                let checkbox = document.createElement("input");

                checkbox_container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = checkbox_index == skill.base;
                checkbox.disabled = base_disabled;

                checkbox.addEventListener("click", (event) => {
                    // updates the cache
                    for(let cache_index = 0; cache_index < this.base_cached_changes.length; cache_index++) {
                        let cached_skill_key = this.base_cached_changes[cache_index].split(";")[0];

                        // if skill_index and the cached_skill_key are equal than there has been made two updates
                        // to the same skill therefore the old is removed from the cache
                        if(parseInt(cached_skill_key) == skill_index) {
                            this.base_cached_changes.splice(cache_index, 1);
                        }
                    }
                    // caches the recent changes
                    this.base_cached_changes.push(`${skill_index};${checkbox_index}`);

                    // updates the html object
                    // remove the check from each element
                    for(let index = 0; index < this.max_base_skill; index++) {
                        checkbox.parentElement.children[index].checked = false;
                    }
                    // add the check onto the current checkbox
                    checkbox.checked = true;
                });
            }
            // generates advanced-value checkboxes from: 6 to 7
            for(let checkbox_index = this.max_base_skill; checkbox_index < this.max_advanced_skill; checkbox_index++) {
                let checkbox = document.createElement("input");

                checkbox_container.appendChild(checkbox);

                checkbox.type = "checkbox";
                checkbox.checked = (checkbox_index - this.max_base_skill) == skill.advanced;
                checkbox.disabled = advanced_disabled;

                checkbox.addEventListener("click", (event) => {
                    // updates the cache
                    for(let cache_index = 0; cache_index < this.advanced_cached_changes.length; cache_index++) {
                        let cached_skill_key = this.advanced_cached_changes[cache_index].split(";")[0];

                        // if skill_index and the cached_skill_key are equal than there has been made two updates
                        // to the same skill therefore the old is removed from the cache
                        if(parseInt(cached_skill_key) == skill_index) {
                            this.advanced_cached_changes.splice(cache_index, 1);
                        }
                    }
                    // caches the recent changes
                    this.advanced_cached_changes.push(`${skill_index};${checkbox_index - this.max_base_skill}`);

                    // updates the html object
                    // remove the check from each element
                    for(let index = this.max_base_skill; index < this.max_advanced_skill; index++) {
                        checkbox.parentElement.children[index].checked = false;
                    }
                    // add the check onto the current checkbox
                    checkbox.checked = true;
                });
            }

        }

        return this.card;
    }

    reset() {
        /* This function performs a total reset of the card object to its status quo */
        // resets the caches
        this.base_cached_changes = Array();
        this.advanced_cached_changes = Array();

        // resets the displayed html
        // loops through all skills
        for(let skill_index = 0; skill_index < this.skills.length; skill_index++) {
            // aquires the current skill
            let skill = this.skills[skill_index];

            // aquires the current html skill container and the checkbox container
            let skill_container = this.card.children[skill_index];
            let checkbox_container = skill_container.children[1];

            // goes through all checkboxes base
            for(let checkbox_index = 0; checkbox_index < this.max_base_skill; checkbox_index++) {
                let checkbox = checkbox_container.children[checkbox_index];

                // resets the value
                checkbox.checked = checkbox_index == skill.base;
            }

            // goes through all checkboxes advanced
            for(let checkbox_index = this.max_base_skill; checkbox_index < this.max_advanced_skill; checkbox_index++) {
                let checkbox = checkbox_container.children[checkbox_index];

                // resets the value. note: checkbox_index has to be subtracted
                checkbox.checked = (checkbox_index - this.max_base_skill) == skill.advanced;
            }
        }
    }
}

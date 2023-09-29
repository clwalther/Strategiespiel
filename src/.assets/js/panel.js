class PanelCard
{
    constructor(name) {
        this.action_button;
        this.action_image;
        this.paragraph;
        this.paragraph_display;

        this.name = name;
    }

    generate() {
        let panel = document.createElement("div");
        let header = document.createElement("h4");
        this.action_button = document.createElement("button");
        this.action_image = document.createElement("img");
        this.paragraph = document.createElement("p");
        this.paragraph_display = document.createElement("p");

        panel.appendChild(header);
        panel.appendChild(this.paragraph);
        panel.appendChild(this.paragraph_display);

        panel.classList.add("panel");

        header.innerText = this.name.length > 11 ? this.name.substring(0, 11) + "..." : this.name;
        this.paragraph.innerText = this.name;
        this.action_image.src = "../../../../assets/icons/edit.svg";

        header.appendChild(this.action_button);
        this.action_button.appendChild(this.action_image);

        return panel;
    }

    add_display(value_text, image_source) {
        let span_display = document.createElement("span");
        let image = document.createElement("img");
        let value = document.createElement("span");

        this.paragraph_display.appendChild(span_display);
        span_display.appendChild(image);
        span_display.appendChild(value);

        image.src = image_source;
        value.innerText = value_text;

        return span_display;
    }
}

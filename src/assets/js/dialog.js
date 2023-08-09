class DialogCard
{
    constructor(name, id) {
        this.header;
        this.paragraph;
        this.container;
        this.cancel;
        this.submit;

        this.name = name;
        this.id = id;
    }

    generate() {
        let dialog = document.createElement("div");
        this.header = document.createElement("h1");
        this.container = document.createElement("div");
        let button_container = document.createElement("div");
        this.cancel = document.createElement("button");
        this.submit = document.createElement("button");

        dialog.appendChild(this.header);
        dialog.appendChild(this.container);
        dialog.appendChild(button_container);

        button_container.appendChild(this.cancel);
        button_container.appendChild(this.submit);

        dialog.id = `dialog-${this.name}-${this.id}`;
        dialog.classList.add(this.name);
        button_container.classList.add("button-container");

        this.submit.style.backgroundColor = "var(--colour-green)";

        return dialog;
    }
}

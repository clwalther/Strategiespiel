(() => {
    // === DIALOG ACTIONS ===
    /* change-teamname */
    (() => {
        let dialog = get_dialog("change-teamname", 0);
        let action_button = get_dialog_action_button("change-teamname", 0);
        let input = dialog.children[1].children[1];


        action_button.addEventListener("click", (event) => {
            send(`TEAMNAME ${} SET ${input.value}`);
        });
    })();
})();

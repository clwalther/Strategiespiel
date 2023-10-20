// === LOGS ===
/**
 * This function updates the logs by fetching the current logs from the backend
 * and putting it into the corresponding element.
 */
function logs_mainloop() {
    // inital call
    fetch_logs();

    // mainloop
    setInterval(() => {
        fetch_logs
    }, 2500); // updating every 2.5 s
}

async function fetch_logs() {
    let codeblock = document.getElementById("logs");

    let fetched_logs = await fetch("./*fetch/logs")
    let synthesized_logs = await fetched_logs.json();

    codeblock.innerHTML = synthesized_logs.join("<br>");
}

/**
 * This function kicks off the mainloop
 */
logs_mainloop();

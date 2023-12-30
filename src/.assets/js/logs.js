(() => {
    const levels = ["debug", "info", "notice", "warning", "error", "fatal"];

    let codeblock = document.getElementById("logs");
    let fetch_timedelta = document.getElementById("logs-fetching-timedelta");

    // === LOGS ===
    async function fetch_logs() {
        let primary_time = performance.now();
        let fetched_logs = await fetch("./*fetch/logs/");
        let secondary_time = performance.now();
        let synthesized_logs = await fetched_logs.json();

        fetch_timedelta.innerText = `[HTTP/1.1 ${fetched_logs.status} ${fetched_logs.statusText} - ${Math.round(secondary_time - primary_time)}ms]`;
        codeblock.innerHTML = "";

        for (let log_index = 0; log_index < synthesized_logs.length; log_index++) {
            const log_element = synthesized_logs[log_index];
            const severity = levels[log_element.message_type];

            const log_timedelta = log_index > 0 ? `+${Math.round(Date.parse(log_element.time_stamp) - Date.parse(synthesized_logs[log_index - 1].time_stamp)) / 1000}s` : "";

            const time_margin =  "".padEnd(6 - log_timedelta.length, " ");
            const severity_margin =  "".padEnd(7 - severity.length, " ");

            codeblock.innerHTML += `<pre><i>(${log_element.time_stamp})${log_timedelta}${time_margin}</i>- <${severity}><b>${severity.toUpperCase()}</b></${severity}>${severity_margin} - ${log_element.context}</pre>`;
        }
    }

    /**
     * This function kicks off the mainloop
     */
    setInterval(() => {
        fetch_logs()
    }, 2500); // updating every 2.5 s
})();

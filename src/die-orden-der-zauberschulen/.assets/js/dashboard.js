(() => {
    // === DASHBOARD ===
    async function fetch_dashbaord() {
        let primary_time = performance.now();
        let fetched_logs = await fetch("./*fetch/");
        let secondary_time = performance.now();
        let synthesized_logs = await fetched_logs.json();
    }

    /**
     * This function kicks off the mainloop
     */
    setInterval(() => {
        fetch_logs()
    }, 2500); // updating every 2.5 s
})();

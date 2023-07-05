<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/view.css">
</head>
<body class="noselect">
    <section>
        <?php if(explode("=", $_SERVER['QUERY_STRING'])[0] == "Job") { ?>
            <h1 id="job-name">Job-Name</h1>
            <img id="job-image" src="../../../assets/imgs/image.png">
            <div id="influence-bar">
                <!-- 12 schools -->
                <!-- order-0 -->
                <div id="0"  style="background-color: var(--color-gryffindor-red)"></div>
                <div id="1"  style="background-color: var(--color-gryffindor-gold)"></div>
                <div id="2"  style="background-color: var(--color-hufflepuff-yellow)"></div>
                <div id="3"  style="background-color: var(--color-hufflepuff-black)"></div>
                <!-- order-1 -->
                <div id="4"  style="background-color: var(--color-ravenclaw-blue)"></div>
                <div id="5"  style="background-color: var(--color-ravenclaw-silver)"></div>
                <div id="6"  style="background-color: var(--color-slytherin-green)"></div>
                <div id="7"  style="background-color: var(--color-slytherin-silver)"></div>
                <!-- order-2 -->
                <div id="8"  style="background-color: var(--color-beauxbatons-blue)"></div>
                <div id="9"  style="background-color: var(--color-beauxbatons-silver)"></div>
                <div id="10" style="background-color: var(--color-durmstrang-red)"></div>
                <div id="11" style="background-color: var(--color-durmstrang-gold)"></div>
            </div>
        <?php } else if(explode("=", $_SERVER['QUERY_STRING'])[0] == "Prestige") { ?>
            <!-- PRESTIGE -->
            <!-- how to calculate prestige??? -->
        <?php } ?>
    </section>
</body>
</html>
<script>
    function on_start() {
        let jobs = ["Medimagier", "Auror", "Minesteriumsbeamter", "Drachenwärter", "Magiezoologe", "Zauberstabsschreiner", "Quidditchprofi"];
        var index = parseInt(window.document.location.search.split("=")[1]);

        document.getElementById("job-name").innerHTML = jobs[index];
    }

    async function log_data() {
        const response = await fetch("/ministry_labour/api.php");
        const data = await response.json();
        update_html(data);
    }

    function update_html(json_data) {
        var index = parseInt(window.document.location.search.split("=")[1]);
        if(Number.isInteger(index)) {
            for(let items in json_data) {
                // division by 10 in the following line is because the data is stored in a range from
                // 0% to 100% with one place after the digit
                document.getElementById(items).style.width = `${json_data[items][index]/10}%`;
            }
        }
    }

    on_start();
    setInterval(log_data, 3000);
</script>

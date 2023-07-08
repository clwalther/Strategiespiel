function highligth_team() {
    if(window.location.search.split("=")[0] == "?Team") {
        let team = window.location.search.split("=")[1];
        document.getElementById(team).classList.add("active_button");
    } else {
        window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=1', '_self');
    }
}

highligth_team();

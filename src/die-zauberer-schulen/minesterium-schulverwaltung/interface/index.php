<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minesterium für Schulverwaltung | Interface</title>

    <link rel="stylesheet" href="../../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../../assets/css/interface.css">

    <script src="../../../../assets/js/interface.js" defer></script>

    <?php

    include "../../../scripts/global.php";

    define('DATABASE', 'MINISTRY_SCHOOL_ADMIN', true);
    define('MAX_VALUE_GRADUATES', 6, true);
    define('MIN_VALUE_GRADUATES', 1, true);

    ?>
</head>
<body>
    <header>
        <a href="/">Strategiespiel</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen">Die-Zauberer-Schulen</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/minesterium-schulverwaltung">Minesterium-Schulverwaltung</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/minesterium-schulverwaltung/interface"><b>Interface</b></a>
    </header>
    <aside>
        <h3>Teams</h3>
        <button id="1" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=1', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 1  -  Teamname</span></button>
        <button id="2" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=2', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 2  -  Teamname</span></button>
        <button id="3" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=3', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 3  -  Teamname</span></button>
        <button id="4" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=4', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 4  -  Teamname</span></button>
        <button id="5" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=5', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 5  -  Teamname</span></button>
        <button id="6" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=6', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 6  -  Teamname</span></button>
        <button id="7" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=7', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 7  -  Teamname</span></button>
        <button id="8" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=8', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 8  -  Teamname</span></button>
        <button id="9" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=9', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 9  -  Teamname</span></button>
        <button id="10" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=10', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 10  -  Teamname</span></button>
        <button id="11" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=11', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 11  -  Teamname</span></button>
        <button id="12" onclick="window.open('/die-zauberer-schulen/minesterium-schulverwaltung/interface/index.php?Team=12', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 12  -  Teamname</span></button>
    </aside>
    <section>
        <h1><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team <?php echo $_GET['Team']; ?>  -  Teamname</span></h1>
        <section>
            <h2>Absolventen</h2>
            <p>
                Schüler Slots:
                <code><?php echo "0"; ?></code>
                <button>+</button>
                <button>-</button>
            </p>
        </section>
        <section>
            <h2>Lehrer</h2>
        </section>
        <section>
            <h2>Gebäude</h2>
        </section>
    </section>
</body>
</html>

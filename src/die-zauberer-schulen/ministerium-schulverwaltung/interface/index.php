<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministerium für Schulverwaltung | Interface</title>

    <link rel="stylesheet" href="../../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../../assets/css/interface.css">

    <script src="../../../../assets/js/interface.js" defer></script>

    <?php

        include "../../../scripts/global.php";

        define('DATABASE', 'MINISTRY_SCHOOL_ADMIN', true);
        define('MAX_VALUE_GRADUATES', 6, true);
        define('MIN_VALUE_GRADUATES', 1, true);
        define('MAX_VALUE_TEACHERS', 3, true);
        define('MIN_VALUE_TEACHERS', 1, true);

    ?>
</head>
<body>
    <nav>
        <a href="/">Strategiespiel</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen">Die-Zauberer-Schulen</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/ministerium-schulverwaltung">Ministerium-Schulverwaltung</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/ministerium-schulverwaltung/interface"><b>Interface</b></a>
    </nav>

    <aside>
        <h3>Teams</h3>
        <button id="1" onclick="window.open('./index.php?Team=1', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 1  -  Teamname</span></button>
        <button id="2" onclick="window.open('./index.php?Team=2', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 2  -  Teamname</span></button>
        <button id="3" onclick="window.open('./index.php?Team=3', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 3  -  Teamname</span></button>
        <button id="4" onclick="window.open('./index.php?Team=4', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 4  -  Teamname</span></button>
        <button id="5" onclick="window.open('./index.php?Team=5', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 5  -  Teamname</span></button>
        <button id="6" onclick="window.open('./index.php?Team=6', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 6  -  Teamname</span></button>
        <button id="7" onclick="window.open('./index.php?Team=7', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 7  -  Teamname</span></button>
        <button id="8" onclick="window.open('./index.php?Team=8', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 8  -  Teamname</span></button>
        <button id="9" onclick="window.open('./index.php?Team=9', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 9  -  Teamname</span></button>
        <button id="10" onclick="window.open('./index.php?Team=10', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 10  -  Teamname</span></button>
        <button id="11" onclick="window.open('./index.php?Team=11', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 11  -  Teamname</span></button>
        <button id="12" onclick="window.open('./index.php?Team=12', '_self');"><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team 12  -  Teamname</span></button>
    </aside>

    <section>
        <h1><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span>Team <?php echo $_GET['Team']; ?>  -  Teamname</span></h1>
        <article>
            <h2>Absolventen</h2>
            <p>
                Absolventen Slots:
                <code><?php echo "0"; ?></code>
            </p>
            <p>
                Unabgeholte Absolventen:
                <code><?php echo "0"; ?></code>
                <button onclick="open_dialog();">Auszahlen</button>
            </p>
        </article>
        <article>
            <h2>Lehrer</h2>
            <p>
                Lehrer Slots:
                <code><?php echo "0"; ?></code>
            </p>
            <div class="noselect">
                <!-- Mock 1: Verteidigung gegen die dunklen Künste -->
                <div>
                    <h4>
                        VgddK
                        <button onclick="open_dialog();">
                            <img src="../../../../assets/imgs/edit.svg">
                        </button>
                    </h4>
                    <p>
                        Verteidigung gegen die dunklen Künste
                    </p>
                    <p style="justify-content: space-around;">
                        <span><img src="../../../../assets/imgs/star.svg"><?php echo "0";?></span>
                        <span><img src="../../../../assets/imgs/education.svg"><?php echo "0";?></span>
                    </p>
                </div>
                <!-- Mock 2: Zaubertränke -->
                <div>
                    <h4>
                        Zaubertränke
                        <button onclick="open_dialog();">
                            <img src="../../../../assets/imgs/edit.svg">
                        </button>
                    </h4>
                    <p>
                        Zaubertränke
                    </p>
                    <p style="justify-content: space-around;">
                        <span><img src="../../../../assets/imgs/star.svg"><?php echo "0"; ?></span>
                        <span><img src="../../../../assets/imgs/education.svg"><?php echo "0"; ?></span>
                    </p>
                </div>
            </div>
        </article>
        <article>
            <h2>Ausbau</h2>
            <article>
                <!-- Parkanlage -->
                <div>
                    <div>
                        <button>Parkanlage</button>
                    </div>
                    <div>
                        <button>Kräutergarten</button>
                        <button>Pavilion</button>
                        <button>Wäldchen</button>
                    </div>
                    <div>
                        <button>Bootsanleger</button>
                        <button>Steinkreis</button>
                    </div>
                    <div>
                        <button>Gewächshaus</button>
                        <button>Försterhütte</button>
                        <button>Drachenhöhle</button>
                    </div>
                </div>
                <!-- Erweiterungsflügel -->
                <div>
                    <div>
                        <button>Erweiterungs-flügel</button>
                    </div>
                    <div>
                        <button>Leherzimmer</button>
                        <button>Große Halle</button>
                    </div>
                    <div>
                        <button>Rektorenturm</button>
                        <button>Rektorenturm</button>
                        <button>Wothturm</button>
                        <button>Atrium</button>
                    </div>
                    <div>
                        <button>Raum der Wünsche</button>
                        <button>Eulerei</button>
                        <button>Brunnen</button>
                    </div>
                </div>
                <!-- Lange Galerie -->
                <div>
                    <div>
                        <button>Lange Galerie</button>
                    </div>
                    <div>
                        <button>Uhrentrum</button>
                        <button>Gemein-schaftsraum</button>
                        <button>Duellier-zimmer</button>
                    </div>
                    <div>
                        <button>Verlies</button>
                        <button>Astronomie-turm</button>
                    </div>
                    <div>
                        <button>Laboratorium</button>
                        <button>Chambre Séparée</button>
                    </div>
                </div>
                <!-- Besenhütte -->
                <div>
                    <div>
                        <button>Besenhütte</button>
                    </div>
                    <div></div>
                    <div>
                        <button>Quidditchfeld</button>
                    </div>
                    <div>
                        <button>Quidditch-tribüne</button>
                    </div>
                </div>
            </article>
        </article>
    </section>

    <footer>

    </footer>

    <dialog id="dialog">
        <div>
            <article>
                <!-- headers -->
                <h1></h1>
                <!-- main bodys -->

            </article>
            <section>
                <button onclick="close_dialog();" style="background-color: var(--colour-accent);">Schließen</button>
                <button style="background-color: var(--colour-green);">Fertig</button>
            </section>
        </div>
    </dialog>

</body>
</html>

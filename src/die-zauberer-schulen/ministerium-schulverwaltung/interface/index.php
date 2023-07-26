<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministerium für Schulverwaltung | Interface</title>

    <script src="../../../../assets/js/script.js" async></script>
    <script src="../../../../assets/js/interface.js" async></script>

    <link rel="stylesheet" href="../../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../../assets/css/interface.css">
</head>
<body id="body">
    <nav>
        <a href="/">Strategiespiel</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen">Die-Zauberer-Schulen</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/ministerium-schulverwaltung">Ministerium-Schulverwaltung</a><i>&nbsp;&nbsp;/&nbsp;&nbsp;</i>
        <a href="/die-zauberer-schulen/ministerium-schulverwaltung/interface"><b>Interface</b></a>
    </nav>

    <aside id="team-drawer">
        <h3>Teams</h3>
        <!-- is beeing filled by javascript with all of the team entries -->
    </aside>

    <section>
        <h1><img src="../../../../assets/imgs/group.svg">&nbsp;&nbsp;<span id="team-name"></span></h1>
        <article>
            <h2>Absolventen</h2>
            <p>
                Unabgeholte Absolventen:
                <code id="students-n-not-fetched"></code>
                <button onclick="open_dialog('dialog-student');">Auszahlen</button>
            </p>
        </article>
        <article>
            <h2>Lehrer</h2>
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
                        <span><img src="../../../../assets/imgs/star.svg"></span>
                        <span><img src="../../../../assets/imgs/education.svg"></span>
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
                        <span><img src="../../../../assets/imgs/star.svg"></span>
                        <span><img src="../../../../assets/imgs/education.svg"></span>
                    </p>
                </div>
            </div>
        </article>
        <article>
            <h2>Ausbau-bau-baum</h2>
            <article id="tree">
                <svg id="tree-connection"></svg>
            </article>
        </article>
    </section>

    <dialog id="dialog">
    </dialog>
</body>
</html>

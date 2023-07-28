<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministerium für Schulverwaltung | Interface</title>

    <link rel="shortcut icon" href="../../../../assets/imgs/favicon-32x32.png" type="image/x-icon">

    <script src="../../../../assets/js/interface.js" async></script>
    <script src="../../../../assets/js/script.js" async></script>

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
        <h1>
            <img src="../../../../assets/imgs/group.svg">
            &nbsp;&nbsp;
            <span id="teamname"></span>
            &nbsp;&nbsp;
            <button onclick="open_dialog('dialog-general-name');">
                <img src="../../../../assets/imgs/edit.svg">
            </button>
        </h1>
        <article>
            <h2>Absolventen</h2>
            <p>
                Unabgeholte Absolventen:
                <code id="students-not-fetched"></code>
                <button onclick="open_dialog('dialog-student');">Auszahlen</button>
            </p>
        </article>
        <article>
            <h2>Lehrer</h2>
            <div class="noselect" id="teachers"></div>
        </article>
        <article>
            <h2>Ausbau-bau-baum</h2>
            <article id="building-tree"></article>
        </article>
    </section>

    <dialog id="dialog">
    </dialog>
</body>
</html>

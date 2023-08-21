<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministerium für Schulverwaltung | Interface</title>

    <link rel="shortcut icon" href="../../../../assets/imgs/favicon-32x32.png" type="image/x-icon">

    <!-- file specific script -->
    <script src="../../../../assets/js/die-zauber-schulen/general/general.js"></script>
    <script src="../../../../assets/js/die-zauber-schulen/interface/school_admin.js"></script>
	<script src="../../../../assets/js/die-zauber-schulen/general/skill.js"></script>
    <!-- standart scripts -->
	<script src="../../../../assets/js/panel.js"></script>
    <script src="../../../../assets/js/dialog.js"></script>
    <!-- main script (import last) -->
    <script src="../../../../assets/js/script.js" async></script>

    <!-- main stylesheet -->
    <link rel="stylesheet" href="../../../../assets/css/style.css">
    <!-- stylistic specific style elements -->
    <link rel="stylesheet" href="../../../../assets/css/section.css">
    <link rel="stylesheet" href="../../../../assets/css/aside.css">
    <link rel="stylesheet" href="../../../../assets/css/dialog.css">
    <!-- file specific style elements -->
    <link rel="stylesheet" href="../../../../assets/css/interface.css">
</head>
<body id="body">
    <!-- NAVIGATION BAR -->
    <nav>
        <a href="/">Strategiespiel</a><i>/</i>
        <a href="/die-zauber-schulen">Die-Zauber-Schulen</a><i>/</i>
        <a href="/die-zauber-schulen/ministerium-schulverwaltung">Ministerium-Schulverwaltung</a><i>/</i>
        <a href="/die-zauber-schulen/ministerium-schulverwaltung/interface"><b>Interface</b></a>
        <div>
            <time></time>
        </div>
    </nav>

    <!-- DRAWER -->
    <aside id="aside">
        <h3>Teams</h3>
        <div id="team-drawer"></div>
    </aside>

    <!-- MAIN SECTION -->
    <section>
        <!-- HEADER -->
        <header>
            <img src="../../../../assets/icons/group.svg">
            <h1 id="teamname"></h1>
            <button onclick="open_dialog('dialog-general-name');">
                <img src="../../../../assets/icons/edit.svg">
            </button>
        </header>
        <!-- STUDENTS -->
        <article id="students">
            <h2>Schüler</h2>
            <p>
                Unabgeholte Schüler: <code id="students-not-fetched"></code>
                <button onclick="open_dialog('dialog-students-0');">
                    Auszahlen
                </button>
            </p>
        </article>
        <!-- TEACHERS -->
        <article id="teachers">
            <h2>Lehrer</h2>
            <div class="noselect" id="teacher-slot"></div>
        </article>
        <!-- BUILDINGS -->
        <article id="buildings">
            <h2>Ausbau-bau-baum</h2>
            <div class="noselect">
                <div id="building-tree">
                    <svg id="buildings-connections"></svg>
                </div>
            </div>
        </article>
    </section>

    <!-- DIALOG -->
    <dialog id="dialog"></dialog>
</body>
</html>

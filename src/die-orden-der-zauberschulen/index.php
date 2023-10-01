<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "../.scripts/imports.php"; ?>
    <?php include "./.scripts/general.php"; ?>
</head>
<body id="body">
    <nav>
        <?php Navigation::construct_nav(__DIR__); ?>
        <div><time></time></div>
    </nav>

    <aside>
        <h3>Directions</h3>
        <div>
            <?php Navigation::construct_drawer(__DIR__); ?>
        </div>
    </aside>

    <section>
        <h1>Die Orden der Zauberschulen - Strategiespiel 2023</h1>
        <article>
            <p>
                Beim diesjährigen Strategiespiel 2023 ”Die Zaubererschulen“ sollen die Teilnehmer passend zum Freizeit Thema ”Harry Potter“ in die faszinierende Welt der Magie und Zauberei eintauchen können.<br>
                Im Spiel übernehmen die Gruppen die Rolle der Schulleiter verschiedener Zauberschulen in Europa nach dem Zweiten Zaubererkrieg.<br>
                Dabei müssen sie durch den Ausbau ihrer Schule und das Anstellen von Lehrern möglichst gute Absolventen hervorbringen, um am meisten Prestige zu erlangen.<br>
            </p>
            <h2>Gringotts Bank</h2>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Non, quos eveniet, esse inventore rem minus veritatis assumenda dolorem quasi cumque maiores. Molestiae rem fuga incidunt eligendi possimus repellat, optio eaque.
            </p>
            <h2>Ministerium für Arbeit</h2>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam labore unde, numquam ex eius, optio suscipit excepturi quasi aperiam iure enim fugit perferendis officiis libero vel? At, doloribus pariatur. Totam.
            </p>
            <h2>Ministerium für Schulverwaltung</h2>
            <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Amet doloribus fuga dicta. Nihil, officiis eum laborum illum, tenetur doloribus omnis nobis accusantium, architecto excepturi aliquid ducimus dignissimos debitis ratione quam.
            </p>
        </article>

        <article>
            <h2>Actions</h2>
            <div class="action-container">
                <section>
                    <div>
                        <h3>Start or resume game</h3>
                        <p>Start background process. CAUTION: this will have immediate affects!</p>
                    </div>
                    <button onclick="open_dialog('start-resume', '0');">Start / Resume</button>
                </section>

                <section>
                    <div>
                        <h3>Stop or pause game</h3>
                        <p>This will halt all background process.</p>
                    </div>
                    <button onclick="open_dialog('stop-pause', '0');">Stop / Pause</button>
                </section>

                <section>
                    <div>
                        <h3>Backup</h3>
                        <p>This will create a backup of all tables of the database "die-orden-der-zauber-schulen".</p>
                    </div>
                    <button onclick="open_dialog('backup', '0');">Backup</button>
                </section>

                <section>
                    <div>
                        <h3>Load backup</h3>
                        <p>Load a backup from the archive. This will create a backup of the current state but than overwrite the current tables in the database "die-orden-der-zauber-schulen".</p>
                    </div>
                    <button onclick="open_dialog('load-backup', '0');">Load backup</button>
                </section>
            </div>
        </article>

        <article>
            <h2>Logs</h2>
            <codeblock id="logs"></codeblock>
        </article>
    </section>

    <!-- DIALOG -->
    <dialog id="dialog">
        <?php Display::start_resume(); ?>
        <?php Display::stop_pause(); ?>
        <?php Display::backup(); ?>
        <?php Display::load_backup(); ?>
    </dialog>
</body>
</html>

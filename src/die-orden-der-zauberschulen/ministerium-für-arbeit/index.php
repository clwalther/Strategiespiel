<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "../../.scripts/imports.php"; ?>
    <?php include "../.scripts/ministry-of-labour.php"; ?>
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
        <header>
            <h1>Ministerium für Arbeit</h1>
        </header>

        <article>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo ea error facilis, distinctio nihil quasi adipisci repellat necessitatibus debitis vero nostrum saepe odio excepturi totam modi doloribus, blanditiis dolores optio!
            </p>

            <h2>Charaktäre</h2>
            <h3>Dean Thomas</h3>

            <h2>Dashboard</h2>
            <p>
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, voluptate quod culpa, illum perferendis quo laudantium, autem omnis ducimus earum nostrum eum dicta similique nemo eveniet. Quo iure vero et!
            </p>

            <h2>Interface</h2>
            <p>
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Facere earum similique unde minus vel, consequuntur molestiae consequatur nihil soluta fugit molestias veniam amet voluptate inventore! Ut deserunt quae cum eius.
            </p>
        </article>

        <article>
            <h2>Actions</h2>
            <div class="action-container">
                <section>
                    <div>
                        <h3>Event <q>Brand von Hogwarts</q> Starten oder Fortfahren</h3>
                        <p>Diese Aktion aktiviert Event and einzahlung für das Event. <warning><b>VORSICHT</b>: Diese Aktion hat direkte und möglicherweise schwerwiegende Konsequenzen!</warning></p>
                    </div>
                    <button onclick="open_dialog('event-fire_of_hogwarts-start-resume', 0);">Start / Fortfahren</button>
                </section>

                <section>
                    <div>
                        <h3>Event <q>Brand von Hogwarts</q> Stoppen oder Pausieren</h3>
                        <p>Diese Aktion deaktiviert Event and einzahlung für das Event. <warning><b>VORSICHT</b>: Diese Aktion hat direkte und möglicherweise schwerwiegende Konsequenzen!</warning></p>
                    </div>
                    <button onclick="open_dialog('event-fire_of_hogwarts-stop-pause', 0);">Stop / Pause</button>
                </section>
            </div>
        </article>
    </section>

    <!-- DIALOG -->
    <dialog id="dialog">
        <?php DisplayMinistryOfLabour::create_dialog_event_fire_of_hogwarts_start_resume(); ?>
        <?php DisplayMinistryOfLabour::create_dialog_event_fire_of_hogwarts_stop_pause(); ?>
    </dialog>
</body>
</html>

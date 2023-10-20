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
        <h1>Ministerium für Arbeit</h1>
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
                        <h3>Start or resume event "Brand von Hogwarts"</h3>
                        <p>This will enable payment for event. CAUTION: this will have immediate affects!</p>
                    </div>
                    <button onclick="open_dialog('start-resume-event', 'fire-of-hogwarts');">Start / Resume event</button>
                </section>

                <section>
                    <div>
                        <h3>Stop or pause event "Brand von Hogwarts"</h3>
                        <p>This will disable payment for event. CAUTION: this will have immediate affects!</p>
                    </div>
                    <button onclick="open_dialog('stop-pause-event', 'fire-of-hogwarts');">Stop / Pause event</button>
                </section>
            </div>
        </article>
    </section>

    <!-- DIALOG -->
    <dialog id="dialog">
        <?php MinistryOfLabourDisplay::start_resume_event_fire_of_hogwarts(); ?>
        <?php MinistryOfLabourDisplay::stop_pause_event_fire_of_hogwarts(); ?>
    </dialog>
</body>
</html>

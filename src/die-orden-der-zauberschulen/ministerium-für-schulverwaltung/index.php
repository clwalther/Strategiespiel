<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "../../.scripts/imports.php"; ?>
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
        <h1>Ministerium für Schulverwaltung</h1>
        <article>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Explicabo ea error facilis, distinctio nihil quasi adipisci repellat necessitatibus debitis vero nostrum saepe odio excepturi totam modi doloribus, blanditiis dolores optio!
            </p>

            <h2>Charaktäre</h2>
            <h3>Padma Patil</h3>
            <h3>Penelope Clearwater</h3>

            <h2>Dashboard</h2>
            <p>
                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Necessitatibus, voluptate quod culpa, illum perferendis quo laudantium, autem omnis ducimus earum nostrum eum dicta similique nemo eveniet. Quo iure vero et!
            </p>

            <h2>Interface</h2>
            <p>
                Lorem ipsum dolor, sit amet consectetur adipisicing elit. Facere earum similique unde minus vel, consequuntur molestiae consequatur nihil soluta fugit molestias veniam amet voluptate inventore! Ut deserunt quae cum eius.
            </p>
        </article>
    </section>
</body>
</html>

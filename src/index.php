<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "./.scripts/imports.php"; ?>
</head>
<body id="body">
    <nav>
        <?php Navigation::construct_nav(__DIR__); ?>
    </nav>
    <aside>
        <h3>Directions</h3>
        <div>
            <?php Navigation::construct_drawer(__DIR__); ?>
        </div>
    </aside>
    <section>
    </section>
</body>
</html>

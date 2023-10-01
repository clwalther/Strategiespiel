<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "../../../.scripts/imports.php"; ?>
    <?php include "../../.scripts/general.php"; ?>
</head>
<body id="body">
    <nav>
        <?php Navigation::construct_nav(__DIR__); ?>
        <div><time></time></div>
    </nav>

	<aside>
		<h3>Exchanges</h3>
		<div>
            <?php Display::exchanges(); ?>
        </div>
        <h3>Teams</h3>
        <div>
            <?php Display::teams(); ?>
        </div>
	</aside>

    <section>
        <h1><?php Display::exchangename(); ?></h1>
    </section>

    <!-- DIALOG -->
    <dialog></dialog>
</body>
</html>

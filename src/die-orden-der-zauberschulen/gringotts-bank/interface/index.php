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
        <?php if (in_array("exchange", array_keys($_GET))) { ?>
            <header>
                <img src="../../../.assets/icons/trending-up.svg">
                <h1><?php Display::exchangename(); ?></h1>
            </header>
        <?php } else if (in_array("team", array_keys($_GET))) { ?>
            <header>
                <img src="../../../.assets/icons/group.svg">
                <h1><?php Display::teamname(); ?></h1>
            </header>
        <?php } ?>
    </section>

    <dialog></dialog>
</body>
</html>

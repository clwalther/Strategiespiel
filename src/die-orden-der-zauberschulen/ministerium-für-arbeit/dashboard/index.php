<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php include "../../../.scripts/imports.php"; ?>
    <?php include "../../.scripts/general.php"; ?>
    <?php include "../../.scripts/ministry-of-school-administration.php"; ?>

    <script src="/die-orden-der-zauberschulen/.assets/js/general.js" defer></script>

    <link rel="stylesheet" href="/die-orden-der-zauberschulen/.assets/css/ministry-of-school-administration.css">
    <link rel="stylesheet" href="/die-orden-der-zauberschulen/.assets/css/skill.css">
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

    </section>

    <dialog id="dialog"></dialog>
</body>
</html>

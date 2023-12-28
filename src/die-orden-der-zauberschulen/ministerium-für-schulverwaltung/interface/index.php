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
		<h3>Teams</h3>
		<div>
            <?php DisplayGeneral::create_drawer_teams(); ?>
        </div>
	</aside>

    <section>
        <header>
            <img src="../../../.assets/icons/group.svg">
            <?php DisplayGeneral::create_h1_teamname(); ?>
            <button onclick="open_dialog('change-teamname', 0);">
                <img src="../../../.assets/icons/edit.svg">
            </button>
        </header>

        <article id="students">
            <h2>Schüler</h2>
            <p>
                Unabgeholte Schüler:
                <?php DisplayMinistryOfSchoolAdministration::create_code_unclaimed_students(); ?>
                <button onclick="open_dialog('students', 0);">
                    Auszahlen
                </button>
            </p>
        </article>

        <article id="teachers">
            <h2>Lehrer</h2>
            <div>
                <?php DisplayMinistryOfSchoolAdministration::create_labels_teachers(); ?>
            </div>
        </article>

        <article id="buildings">
            <h2>Ausbau-bau-baum</h2>
            <div class="noselect">
                <div>
                    <?php DisplayMinistryOfSchoolAdministration::create_label_tree_buildings(); ?>
                </div>
            </div>
        </article>
    </section>

    <dialog id="dialog">
        <?php DisplayGeneral::create_dialog_change_teamname(); ?>
        <?php DisplayMinistryOfSchoolAdministration::create_dialog_unclaimed_students(); ?>
    </dialog>
</body>
</html>

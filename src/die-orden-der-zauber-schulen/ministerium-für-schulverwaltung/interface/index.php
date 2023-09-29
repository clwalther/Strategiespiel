<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- file specific script -->
    <script src="../../../.assets/js/general/general.js"></script>
	<script src="../../../.assets/js/general/skill.js"></script>
    <script src="../../../.assets/js/interface/school-admin.js"></script>

    <?php include "../../../.scripts/imports.php"; ?>
    <?php include "../../.scripts/general.php"; ?>
    <?php include "../../.scripts/ministy-of-school-administration.php"; ?>
</head>
<body id="body">
    <nav>
        <?php Navigation::construct_nav(__DIR__); ?>
        <div><time></time></div>
    </nav>

	<aside>
		<h3>Teams</h3>
		<div>
            <?php Display::teams(); ?>
        </div>
	</aside>

    <section>
        <!-- HEADER -->
        <header>
            <img src="../../../.assets/icons/group.svg">
            <h1><?php Display::teamname(); ?></h1>
            <button onclick="open_dialog('dialog-general-name');">
                <img src="../../../.assets/icons/edit.svg">
            </button>
        </header>

        <!-- STUDENTS -->
        <article id="students">
            <h2>Schüler</h2>
            <p>
                Unabgeholte Schüler:
                <code><?php MinistryOfSchoolAdministrationDisplay::n_unfetched_students(); ?></code>
                <button onclick="open_dialog('dialog-students-0');">
                    Auszahlen
                </button>
            </p>
        </article>

        <!-- TEACHERS -->
        <article id="teachers">
            <h2>Lehrer</h2>
            <div class="noselect">
                <?php MinistryOfSchoolAdministrationDisplay::teachers(); ?>
            </div>
        </article>

        <!-- BUILDINGS -->
        <article id="buildings">
            <h2>Ausbau-bau-baum</h2>
            <div class="noselect">
                <div>
                    <svg></svg>
                </div>
            </div>
        </article>
    </section>

    <!-- DIALOG -->
    <dialog></dialog>
</body>
</html>

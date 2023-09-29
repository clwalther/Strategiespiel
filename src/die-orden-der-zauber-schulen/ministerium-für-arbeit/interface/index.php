<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- file specific script -->
    <script src="../../.assets/js/general/general.js"></script>
    <script src="../../.assets/js/interface/labour.js"></script>
	<script src="../../.assets/js/general/skill.js"></script>

    <?php include "../../../.scripts/imports.php"; ?>
    <?php include "../../.scripts/general.php"; ?>
    <?php include "../../.scripts/ministy-of-labour.php"; ?>
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
        </header>

		<!-- PRESTIGE -->
		<article id="prestige">
			<h2>Prestige</h2>
			<p>
				Insgesamte Prestige: <code id="prestige-accumulated"></code>
				<button onclick="open_dialog('dialog-prestige-0');">
					Einzahlen
				</button>
			</p>
		</article>

		<!-- JOBS -->
        <article id="jobs">
            <h2>Arbeiter</h2>
            <div class="noselect" id="job-slot"></div>
        </article>

		<!-- EVENT: BRAND VON HOGWARTS -->
		<article id="fire-of-hogwarts" class="disabled-event">
			<h2>Brand von Hogwarts</h2>
			<p>
				Anteil am Neubau: <code id="fire-of-hogwarts-share"></code>
				<button onclick="open_dialog('dialog-fire-of-hogwarts-0');">
					Einzahlen
				</button>
			</p>
		</article>
	</section>

	<dialog></dialog>
</body>
</html>

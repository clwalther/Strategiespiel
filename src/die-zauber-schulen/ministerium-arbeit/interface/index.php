<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ministerium für Arbeit | Interface</title>

	<link rel="shortcut icon" href="../../../../assets/imgs/favicon-32x32.png" type="image/x-icon">

	<!-- file specific script -->
	<script src="../../../../assets/js/general.js"></script>
	<script src="../../../../assets/js/dialog.js"></script>
	<script src="../../../../assets/js/skill.js"></script>
	<script src="../../../../assets/js/panel.js"></script>
	<script src="../../../../assets/js/labour.js"></script>
	<!-- main script (import last) -->
	<script src="../../../../assets/js/script.js" async></script>

	<!-- main stylesheet -->
	<link rel="stylesheet" href="../../../../assets/css/style.css">
	<!-- stylistic specific style elements -->
	<link rel="stylesheet" href="../../../../assets/css/section.css">
	<link rel="stylesheet" href="../../../../assets/css/aside.css">
	<link rel="stylesheet" href="../../../../assets/css/dialog.css">
	<!-- file specific style elements -->
	<link rel="stylesheet" href="../../../../assets/css/interface.css">
</head>
<body id="body">
	<!-- NAVIGATION BAR -->
	<nav>
		<a href="/">Strategiespiel</a><i>/</i>
		<a href="/die-zauber-schulen">Die-Zauber-Schulen</a><i>/</i>
		<a href="/die-zauber-schulen/ministerium-arbeit">Ministerium-Arbeit</a><i>/</i>
		<a href="/die-zauber-schulen/ministerium-arbeit/interface"><b>Interface</b></a>
		<div>
			<time></time>
		</div>
	</nav>

	<!-- DRAWER -->
	<aside id="aside">
		<h3>Teams</h3>
		<div id="team-drawer"></div>
	</aside>

	<!-- MAIN SECTION -->
	<section>
		<!-- HEADER -->
        <header>
            <img src="../../../../assets/imgs/group.svg">
            <h1 id="teamname"></h1>
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
	</section>

	<!-- DIALOG -->
	<dialog id="dialog"></dialog>
</body>
</html>

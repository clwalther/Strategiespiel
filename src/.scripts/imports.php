<?php
    // standart include
    include "/var/www/html/Strategiespiel/src/.scripts/document.php";
    include "/var/www/html/Strategiespiel/src/.scripts/navigation.php";

    if ($_SERVER['REQUEST_URI'] != '/' && is_dir(dirname($_SERVER['SCRIPT_FILENAME'])) && !strpos(dirname($_SERVER['SCRIPT_FILENAME']), "*") && !strpos(dirname($_SERVER['SCRIPT_FILENAME']), ".")) {

        // include and initate only when in a specific game folder
        include "/var/www/html/Strategiespiel/src/.scripts/database.php";
        include "/var/www/html/Strategiespiel/src/.scripts/environment.php";

        $conf_folder_name = explode("/", $_SERVER['PHP_SELF'])[1];
        $conf_folder_path = "/var/www/html/Strategiespiel/conf.d/";

        $environment = new EnvironmentHandler($conf_folder_path.$conf_folder_name."/.env");
        $configuration = json_decode(file_get_contents($conf_folder_path.$conf_folder_name."/config.json"));

        $database = new DatabaseHandler(
            $environment->get("DATABASE_NAME"),
            $environment->get("SERVERNAME"),
            $environment->get("USER_LOGIN"),
            $environment->get("USERNAME")
        );

        $database->connect();
    }
?>

<!-- title-->
<title><?php Navigation::construct_title(); ?></title>

<!-- main stylesheet -->
<link rel="stylesheet" href="/../.assets/css/style.css">
<!-- stylistic specific style elements -->
<link rel="stylesheet" href="/../.assets/css/section.css">
<link rel="stylesheet" href="/../.assets/css/navigation.css">
<link rel="stylesheet" href="/../.assets/css/dialog.css">

<!-- main script (import last) -->
<script src="/../.assets/js/script.js" defer></script>

<!-- favicon -->
<link rel="shortcut icon" href="/../.assets/imgs/favicon-32x32.png" type="image/x-icon">

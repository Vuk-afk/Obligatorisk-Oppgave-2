<?php
/* db-tilkobling */
mysqli_report(MYSQLI_REPORT_OFF);
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');
$db = mysqli_connect($host, $username, $password, $database) or die('ikke kontakt med database-server');
mysqli_set_charset($db, 'utf8mb4');
/* tilkobling til database-serveren utført */
?>

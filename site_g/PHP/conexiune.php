<?php
// Parse the config file
$config = require 'config.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db   = $config['db'];

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die("Eroare conectare MySQL: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");
?>
